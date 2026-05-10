<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class RecommendationService
{
    protected Client $client;
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.recommendation.url',
            env('PYTHON_RECOMMENDATION_API_URL', 'http://127.0.0.1:5000/recommend'));

        $this->client = new Client([
            'timeout'         => 30,
            'connect_timeout' => 10,
            'headers'         => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ],
        ]);
    }

    /**
     * Interroge l'API Python de recommandation de filières.
     *
     * @param  float  $scoreFg      Score du baccalauréat (0–200)
     * @param  string $riasecInput  Code RIASEC en 3 lettres (ex: ISA)
     * @return array{
     *     success: bool,
     *     data: array|null,
     *     error: string|null
     * }
     */
    public function getRecommendations(float $scoreFg, string $riasecInput): array
    {
        try {
            $response = $this->client->post($this->apiUrl, [
                'json' => [
                    'score_fg'     => $scoreFg,
                    'riasec_input' => strtoupper(trim($riasecInput)),
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return [
                    'success' => false,
                    'data'    => null,
                    'error'   => 'Réponse invalide reçue depuis le serveur de recommandations.',
                ];
            }

            return [
                'success' => true,
                'data'    => $body,
                'error'   => null,
            ];

        } catch (ConnectException $e) {
            Log::error('RecommendationService – Impossible de joindre l\'API Python', [
                'url'     => $this->apiUrl,
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'data'    => null,
                'error'   => 'Le service de recommandations est inaccessible. Vérifiez que le serveur Python est démarré sur le port 5000.',
            ];

        } catch (RequestException $e) {
            $statusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 0;

            Log::error('RecommendationService – Erreur HTTP de l\'API Python', [
                'url'        => $this->apiUrl,
                'status'     => $statusCode,
                'message'    => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'data'    => null,
                'error'   => "L'API de recommandations a retourné une erreur (HTTP $statusCode). Veuillez réessayer.",
            ];

        } catch (\Throwable $e) {
            Log::error('RecommendationService – Erreur inattendue', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'data'    => null,
                'error'   => 'Une erreur inattendue est survenue. Veuillez réessayer.',
            ];
        }
    }
}
