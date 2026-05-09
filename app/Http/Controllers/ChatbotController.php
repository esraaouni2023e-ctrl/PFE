<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Modèles à essayer dans l'ordre (fallback si quota dépassé)
     * On inclut les versions 2.5, 2.0 et 1.5 pour maximiser la disponibilité.
     */
    private array $models = [
        'gemini-2.0-flash',
        'gemini-2.5-flash',
        'gemini-2.0-flash-001',
        'gemini-1.5-flash',
        'gemini-2.0-flash-lite',
        'gemini-2.5-flash-lite',
        'gemini-1.5-flash-8b',
        'gemini-2.5-pro',
    ];

    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1/models/';

    /**
     * Contexte système injecté au début de chaque conversation.
     * Il donne à Gemini le rôle d'un conseiller d'orientation scolaire.
     */
    private string $systemPrompt = <<<PROMPT
Tu es "CapAvenir IA", un assistant d'orientation scolaire et professionnelle intelligent, bienveillant et expert.
Tu aides les étudiants tunisiens à choisir leur filière, comprendre les formations universitaires, explorer les métiers,
et planifier leur avenir académique et professionnel.

Règles importantes :
- Réponds toujours en français, avec un ton chaleureux, encourageant et professionnel.
- Sois concis (max 3-4 phrases par réponse sauf si l'étudiant demande plus de détails).
- Si la question ne concerne pas l'orientation/éducation/carrière, redirige poliment vers ces thèmes.
- Utilise des emojis de façon modérée pour rendre les réponses vivantes.
- Donne des conseils personnalisés et actionnables.
PROMPT;

    /**
     * Traite le message de l'étudiant et retourne la réponse de Gemini.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chat(Request $request)
    {
        // Validation
        $request->validate([
            'message'  => 'required|string|max:1000',
            'history'  => 'nullable|array',
            'history.*.role'    => 'required_with:history|string|in:user,model',
            'history.*.content' => 'required_with:history|string|max:2000',
        ]);

        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey)) {
            return response()->json([
                'error' => '🔑 Clé API Gemini non configurée. Contactez l\'administrateur.',
            ], 500);
        }

        // Construire l'historique de conversation
        $contents = [];

        // Contexte système (workaround : injecté comme premier échange)
        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $this->systemPrompt]],
        ];
        $contents[] = [
            'role'  => 'model',
            'parts' => [['text' => 'Compris ! Je suis CapAvenir IA, prêt à vous aider dans votre orientation. 🎓']],
        ];

        // Historique de la session
        if (!empty($request->history)) {
            foreach ($request->history as $msg) {
                $contents[] = [
                    'role'  => $msg['role'],
                    'parts' => [['text' => $msg['content']]],
                ];
            }
        }

        // Message actuel
        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $request->message]],
        ];

        $payload = [
            'contents'         => $contents,
            'generationConfig' => [
                'temperature'     => 0.75,
                'topK'            => 40,
                'topP'            => 0.95,
                'maxOutputTokens' => 400,
            ],
            'safetySettings' => [
                ['category' => 'HARM_CATEGORY_HARASSMENT',        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_HATE_SPEECH',       'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
            ],
        ];

        $lastError = null;

        // ── Essayer chaque modèle dans l'ordre (fallback automatique) ──
        foreach ($this->models as $model) {
            try {
                $url      = $this->baseUrl . $model . ':generateContent?key=' . $apiKey;
                $response = Http::timeout(25)->post($url, $payload);

                // Quota ou rate-limit → attendre un tout petit peu et essayer le modèle suivant
                if ($response->status() === 429) {
                    Log::warning("Gemini quota on [{$model}], trying next...");
                    $lastError = 'quota';
                    usleep(100000); // 100ms de délai pour laisser respirer l'API
                    continue;
                }

                // Clé invalide → inutile d'essayer les autres
                if ($response->status() === 400) {
                    $body = $response->json();
                    if (str_contains($body['error']['message'] ?? '', 'API key not valid')) {
                        return response()->json([
                            'error' => '🔑 Clé API Gemini invalide. Rendez-vous sur aistudio.google.com pour en créer une.',
                        ], 500);
                    }
                }

                // Modèle introuvable → essayer le suivant
                if ($response->status() === 404) {
                    Log::warning("Gemini model [{$model}] not found, trying next…");
                    $lastError = 'not_found';
                    continue;
                }

                // Autre erreur HTTP
                if ($response->failed()) {
                    Log::error('Gemini API error', ['model' => $model, 'status' => $response->status(), 'body' => $response->body()]);
                    $lastError = 'api_error';
                    continue;
                }

                // ✅ Succès — extraire la réponse
                $data  = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if (!$reply) {
                    $finishReason = $data['candidates'][0]['finishReason'] ?? 'UNKNOWN';
                    if ($finishReason === 'SAFETY') {
                        return response()->json([
                            'reply' => '⚠️ Je ne peux pas répondre à cette question. Reformulez ou posez-moi une question sur votre orientation.',
                        ]);
                    }
                    $lastError = 'empty_response';
                    continue;
                }

                Log::info("Gemini responded via [{$model}]");
                return response()->json(['reply' => trim($reply)]);

            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                Log::error("Gemini connection failed [{$model}]", ['error' => $e->getMessage()]);
                $lastError = 'connection';
                continue;
            }
        }

        // Tous les modèles ont échoué
        $errorMessages = [
            'quota'          => '⏳ Quota API momentanément dépassé. Réessayez dans 1-2 minutes.',
            'connection'     => '⚡ Connexion à l\'assistant IA impossible. Vérifiez votre réseau.',
            'not_found'      => '⚙️ Modèle IA indisponible. Contactez l\'administrateur.',
            'empty_response' => '🤔 L\'assistant n\'a pas pu générer de réponse. Réessayez.',
        ];

        return response()->json([
            'error' => $errorMessages[$lastError] ?? '⚡ L\'assistant IA est temporairement indisponible. Réessayez dans un instant.',
        ], 503);
    }
}
