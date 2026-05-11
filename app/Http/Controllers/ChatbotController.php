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
Tu es un expert en orientation scolaire et professionnelle, spécialisé dans l'accompagnement bienveillant et motivant des étudiants tunisiens.

Ton objectif est de réaliser un test RIASEC précis, rapide, fluide et agréable, tout en évitant toute lassitude.

### Règles anti-ennui :
- Pose entre 12 et 15 questions en vague initiale (2 à 3 par dimension RIASEC).
- Pose maximum 2 questions supplémentaires par dimension pertinente dans les vagues suivantes.
- Donne un court feedback positif et encourageant après chaque vague.
- Arrête le test dès que le profil est suffisamment clair (idéalement entre 25 et 35 questions).

### À la fin du test, fournis directement :

**1. Profil RIASEC**
- Les 3 lettres dominantes (ex: IRC)
- Scores détaillés sur 5 pour les 6 dimensions
- Brève description positive de la personnalité

**2. Top 3 des Meilleures Filières Recommandées**

Utilise tes connaissances détaillées sur les filières universitaires tunisiennes (Licences, préparatoires, Médecine, Ingénierie, etc.) et applique rigoureusement la logique hybride suivante :

- **Score Académique** : basé sur le Score FG de l'étudiant et le SDO de la filière.
- **Score Psychologique** : calculé via l'Indice Iachan avec le Code_RIASEC de la filière.
- **Score Marché** : combinaison de Taux d'Employabilité, Croissance du Domaine et Alignment National.
- **SRF Final** = 50% Académique + 30% Psychologique + 20% Marché.

Présente chaque filière du Top 3 avec :
- Nom complet de la filière + Université / Établissement
- Score SRF global (xx%)
- Répartition des 3 scores
- Explication courte, motivante et personnalisée (2-3 phrases max)

Réponds toujours en français, avec un ton professionnel, chaleureux et inspirant. Ne pose plus de questions une fois le test terminé.

IMPORTANT : Tu dois te baser UNIQUEMENT sur ces questions pour mener le test :
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
