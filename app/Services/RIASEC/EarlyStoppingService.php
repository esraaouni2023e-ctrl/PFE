<?php

namespace App\Services\RIASEC;

use App\Models\AnswerRiasec;
use App\Models\QuestionRiasec;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class EarlyStoppingService
{
    private Client $client;
    private string $pythonApiUrl;

    public function __construct()
    {
        $this->client = new Client(['timeout' => 5.0]);
        $this->pythonApiUrl = env('PYTHON_API_URL', 'http://127.0.0.1:5000');
    }

    /**
     * Calcule l'historique des scores par bloc et interroge l'API Python.
     * Un bloc correspond par défaut à 15 questions.
     */
    public function checkStability(string $sessionId, int $currentStep, int $blockSize = 15): array
    {
        // On vérifie si on est exactement à la fin d'un bloc
        if ($currentStep % $blockSize !== 0) {
            return ['stop' => false, 'confidence' => null, 'message' => null];
        }

        $blockIndex = (int) floor($currentStep / $blockSize);
        
        // Charger toutes les réponses jusqu'à l'étape actuelle
        $answers = AnswerRiasec::session($sessionId)
            ->avecQuestion()
            ->orderBy('id')
            ->get();

        $scoresHistory = [];
        
        // Diviser les réponses en blocs et calculer le score cumulé
        for ($i = 1; $i <= $blockIndex; $i++) {
            $limit = $i * $blockSize;
            $blockAnswers = $answers->take($limit);
            $scoresHistory[] = $this->calculateRawScores($blockAnswers);
        }

        try {
            $response = $this->client->post("{$this->pythonApiUrl}/check_stability", [
                'json' => [
                    'scores_history' => $scoresHistory,
                    'block_index'    => $blockIndex,
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            return [
                'stop'       => $result['stop'] ?? false,
                'confidence' => $result['confidence'] ?? 0,
                'trigramme'  => $result['trigramme'] ?? '',
                'message'    => $result['message'] ?? '',
                'block_index'=> $blockIndex,
            ];
            
        } catch (\Exception $e) {
            Log::error("Erreur EarlyStoppingService: " . $e->getMessage());
            return ['stop' => false, 'confidence' => null, 'message' => null];
        }
    }

    /**
     * Calcule les scores bruts pour un sous-ensemble de réponses.
     */
    private function calculateRawScores($answers): array
    {
        $rawScores = ['R' => 0, 'I' => 0, 'A' => 0, 'S' => 0, 'E' => 0, 'C' => 0];

        foreach ($answers as $answer) {
            $question = $answer->question;
            if (!$question || !isset($rawScores[$question->dimension])) {
                continue;
            }

            $dim = $question->dimension;
            $poids = $question->poids ?? 1;

            $valeur = $answer->valeur;
            if ($question->is_reverse && $question->type_reponse === 'likert') {
                $valeur = 6 - $valeur;
            }

            $rawScores[$dim] += $valeur * $poids;
        }

        return $rawScores;
    }
}
