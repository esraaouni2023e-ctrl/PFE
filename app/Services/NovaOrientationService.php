<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NovaOrientationService
{
    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1/models/';
    
    // Using flash models for speed and reliability, falling back to pro if needed
    private array $models = [
        'gemini-2.0-flash',
        'gemini-2.5-flash',
        'gemini-1.5-flash',
        'gemini-2.5-pro',
    ];

    private string $systemPrompt = <<<PROMPT
Tu es **Nova**, un conseiller d'orientation universitaire expert, analytique, bienveillant et hautement professionnel. Tu accompagnes les bacheliers tunisiens dans le choix de leur filière universitaire avec rigueur, précision et empathie.

Ton processus de raisonnement est structuré en deux étapes obligatoires :

### 1. Moteur de Calcul – Formule Globale (FG)
Avant toute analyse, tu dois calculer (ou valider) le **Score Formule Globale (FG)** selon la section du Baccalauréat de l'étudiant en utilisant strictement les formules suivantes :

**Formules Officielles :**

- **Lettres** : FG = 4*MG + 1.5*A + 1.5*PH + 1*HG + 1*F + 1*Ang
- **Mathématiques** : FG = 4*MG + 2*M + 1.5*SP + 0.5*SVT + 1*F + 1*Ang
- **Sciences expérimentales** : FG = 4*MG + 1*M + 1.5*SP + 1.5*SVT + 1*F + 1*Ang
- **Économie et gestion** : FG = 4*MG + 1.5*Ec + 1.5*Ge + 0.5*M + 0.5*HG + 1*F + 1*Ang
- **Technique** : FG = 4*MG + 1.5*TE + 1.5*M + 1*SP + 1*F + 1*Ang
- **Informatique** : FG = 4*MG + 1.5*Algo + 0.5*SP + 0.5*STI + 1*F + 1*Ang
- **Sport** : FG = 4*MG + 1.5*SB + 1*Sp-sport + 0.5*EP + 0.5*SP + 0.5*PH + 1*F + 1*Ang

(Légende : MG = Moyenne Générale, A = Arabe, PH = Philosophie, HG = Histoire-Géo, F = Français, Ang = Anglais, M = Mathématiques, SP = Sciences Physiques, SVT = SVT, Ec = Économie, Ge = Gestion, TE = Technologie, Algo = Algorithmique, STI = Systèmes & Technologies Informatiques, SB = Sciences Biologiques, etc.)

Format de sortie obligatoire (JSON strict) :
Tu dois répondre uniquement avec un objet JSON valide, contenant uniquement le score calculé. N'ajoute aucun texte avant ou après :
{
  "section_bac": "Mathématiques",
  "score_fg": 158.45
}
PROMPT;

    /**
     * Analyzes the student profile and returns a JSON array of recommendations.
     *
     * @param array $studentData
     * @return array|null
     */
    public function analyzeProfile(array $studentData): ?array
    {
        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey)) {
            Log::error('NovaOrientationService: Gemini API key is missing.');
            throw new \Exception('Clé API Gemini non configurée.');
        }

        // Calculate FG using Gemini AI
        $userPrompt = "Voici les données de l'étudiant :\n" . json_encode($studentData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $this->systemPrompt . "\n\n" . $userPrompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.4, // Lower temperature for more deterministic/calculative output
                'topK' => 32,
                'topP' => 0.95,
                'responseMimeType' => 'application/json', // Force JSON output if the model supports it
            ],
        ];

        $lastError = null;

        foreach ($this->models as $model) {
            try {
                $url = $this->baseUrl . $model . ':generateContent?key=' . $apiKey;
                $response = Http::timeout(30)->post($url, $payload);

                if ($response->status() === 429) {
                    Log::warning("Nova Gemini quota on [{$model}], trying next...");
                    $lastError = 'quota';
                    usleep(100000);
                    continue;
                }

                if ($response->status() === 400 || $response->status() === 404) {
                    Log::warning("Nova Gemini bad request or not found [{$model}]: " . $response->body());
                    $lastError = 'api_error';
                    continue;
                }

                if ($response->failed()) {
                    Log::error("Nova Gemini API error [{$model}]", ['status' => $response->status(), 'body' => $response->body()]);
                    $lastError = 'api_error';
                    continue;
                }

                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if (!$reply) {
                    $lastError = 'empty_response';
                    continue;
                }

                // Clean the response if Gemini added markdown formatting around JSON
                $reply = str_replace(['```json', '```'], '', $reply);
                
                $decoded = json_decode(trim($reply), true);
                
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                } else {
                    Log::error("Nova JSON decode error: " . json_last_error_msg(), ['reply' => $reply]);
                    throw new \Exception('La réponse de l\'IA n\'est pas un JSON valide.');
                }

            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                Log::error("Nova Gemini connection failed [{$model}]", ['error' => $e->getMessage()]);
                $lastError = 'connection';
                continue;
            }
        }

        throw new \Exception('Impossible de contacter l\'assistant Nova pour le moment. (' . $lastError . ')');
    }
}
