<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
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

    private function getSystemPrompt(): string
    {
        $questions = \App\Models\QuestionRiasec::where('actif', true)->get()->groupBy('dimension');
        $formattedQuestions = [
            'Realistic' => $questions->get('R', collect())->pluck('texte_fr')->toArray(),
            'Investigative' => $questions->get('I', collect())->pluck('texte_fr')->toArray(),
            'Artistic' => $questions->get('A', collect())->pluck('texte_fr')->toArray(),
            'Social' => $questions->get('S', collect())->pluck('texte_fr')->toArray(),
            'Enterprising' => $questions->get('E', collect())->pluck('texte_fr')->toArray(),
            'Conventional' => $questions->get('C', collect())->pluck('texte_fr')->toArray(),
        ];
        $jsonQuestions = json_encode($formattedQuestions, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return <<<PROMPT
Tu es ORIENTIA, l'assistant intelligent et conseiller d'orientation de la plateforme "CapAvenir" (une plateforme d'orientation académique et professionnelle pour les étudiants tunisiens).

Tes objectifs sont :
1. Répondre aux questions des étudiants concernant la plateforme CapAvenir et ses fonctionnalités.
2. Réaliser le test d'orientation RIASEC si l'étudiant le souhaite.

### À propos de la plateforme CapAvenir :
La plateforme offre plusieurs outils pour aider les étudiants tunisiens dans leur orientation :
- **Test RIASEC** : Un test psychométrique pour découvrir sa personnalité et obtenir des recommandations de filières.
- **Simulateur What-If** : Permet aux étudiants d'entrer leurs notes et de simuler leur score d'orientation pour voir à quelles filières ils peuvent accéder.
- **Comparateur de filières** : Pour comparer plusieurs formations côte à côte (taux d'employabilité, scores requis, etc.).
- **Vœux (Wishlist)** : Pour sauvegarder et réorganiser les filières favorites.
- **Portfolio & Roadmap** : Pour générer un plan de carrière et suivre ses compétences.
- **Messagerie** : Pour contacter des conseillers d'orientation directement sur la plateforme.
Si un étudiant pose une question sur ces fonctionnalités, explique-les clairement et de manière concise.

### Règles pour le test RIASEC (si l'étudiant veut faire le test) :
- Pose entre 12 et 15 questions en vague initiale (2 à 3 par dimension RIASEC).
- Pose maximum 2 questions supplémentaires par dimension pertinente dans les vagues suivantes.
- Donne un court feedback positif et encourageant après chaque vague.
- Arrête le test dès que le profil est suffisamment clair (idéalement entre 25 et 35 questions).

À la fin du test RIASEC, fournis directement :
**1. Profil RIASEC** : Les 3 lettres dominantes (ex: IRC), les scores sur 5 pour les 6 dimensions, et une brève description de la personnalité.
**2. Top 3 des Meilleures Filières Recommandées** : Basé sur le Score FG (académique), l'Indice Iachan (psychologique) et le marché (employabilité). Présente chaque filière avec son score SRF global et une courte explication.

Réponds toujours en français, avec un ton professionnel, chaleureux, bienveillant et inspirant.

IMPORTANT : Pour le test RIASEC, tu dois te baser UNIQUEMENT sur ces questions :
$jsonQuestions
PROMPT;
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array',
            'history.*.role' => 'required_with:history|string|in:user,model',
            'history.*.content' => 'required_with:history|string|max:2000',
        ]);

        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey)) {
            return response()->json([
                'error' => 'Cle API Gemini non configuree. Contactez l administrateur.',
            ], 500);
        }

        $contents = [
            [
                'role' => 'user',
                'parts' => [['text' => $this->getSystemPrompt()]],
            ],
            [
                'role' => 'model',
                'parts' => [[
                    'text' => 'Compris. Je suis le conseiller d\'orientation psychologique intelligent. Je suis prêt à accompagner l\'étudiant pour découvrir son profil RIASEC et lui faire des recommandations.',
                ]],
            ],
        ];

        foreach ($request->input('history', []) as $msg) {
            $contents[] = [
                'role' => $msg['role'],
                'parts' => [['text' => $msg['content']]],
            ];
        }

        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $request->message]],
        ];

        $payload = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.65,
                'topK' => 40,
                'topP' => 0.95,
                'maxOutputTokens' => 500,
            ],
            'safetySettings' => [
                ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
            ],
        ];

        $lastError = null;

        foreach ($this->models as $model) {
            try {
                $url = $this->baseUrl . $model . ':generateContent?key=' . $apiKey;
                $response = Http::timeout(25)->post($url, $payload);

                if ($response->status() === 429) {
                    Log::warning("Gemini quota on [{$model}], trying next...");
                    $lastError = 'quota';
                    usleep(100000);
                    continue;
                }

                if ($response->status() === 400) {
                    $body = $response->json();
                    if (str_contains($body['error']['message'] ?? '', 'API key not valid')) {
                        return response()->json([
                            'error' => 'Cle API Gemini invalide. Verifiez la configuration.',
                        ], 500);
                    }
                }

                if ($response->status() === 404) {
                    Log::warning("Gemini model [{$model}] not found, trying next...");
                    $lastError = 'not_found';
                    continue;
                }

                if ($response->failed()) {
                    Log::error('Gemini API error', [
                        'model' => $model,
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    $lastError = 'api_error';
                    continue;
                }

                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if (!$reply) {
                    $finishReason = $data['candidates'][0]['finishReason'] ?? 'UNKNOWN';
                    if ($finishReason === 'SAFETY') {
                        return response()->json([
                            'reply' => 'Je ne peux pas repondre a cette question. Reformulez ou posez-moi une question sur votre orientation.',
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

        $errorMessages = [
            'quota' => 'Quota API momentanement depasse. Reessayez dans 1-2 minutes.',
            'connection' => 'Connexion a ORIENTIA impossible. Verifiez votre reseau.',
            'not_found' => 'Modele IA indisponible. Contactez l administrateur.',
            'empty_response' => 'ORIENTIA n a pas pu generer de reponse. Reessayez.',
        ];

        return response()->json([
            'error' => $errorMessages[$lastError] ?? 'ORIENTIA est temporairement indisponible. Reessayez dans un instant.',
        ], 503);
    }
}
