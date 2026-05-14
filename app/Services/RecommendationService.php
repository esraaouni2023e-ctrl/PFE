<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class RecommendationService
{
    protected $client;
    protected $pythonApiUrl;

    public function __construct()
    {
        $this->client = new Client(['timeout' => 30.0]);
        $this->pythonApiUrl = env('PYTHON_API_URL', 'http://localhost:5000'); // URL de l'API Python
    }

    /**
     * Obtient les recommandations depuis l'API Python
     *
     * @param array $profilEtudiant
     * @param mixed $filiereActuelle (peut être le nom de la filière en string ou un tableau selon le besoin)
     * @param int $topN
     * @return array
     */
    public function getRecommendations(array $profilEtudiant, $filiereActuelle = null, int $topN = 12)
    {
        try {
            // Note: l'API app.py attend 'filiere_actuelle' comme nom (string)
            $filiereActuelleValue = is_array($filiereActuelle) && isset($filiereActuelle['Nom_Filiere']) 
                ? $filiereActuelle['Nom_Filiere'] 
                : $filiereActuelle;

            $response = $this->client->post("{$this->pythonApiUrl}/recommend", [
                'json' => [
                    'profil_etudiant' => $profilEtudiant,
                    'filiere_actuelle' => $filiereActuelleValue,
                    'top_n' => $topN,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'appel à l'API Python: " . $e->getMessage());
            return ['error' => 'Impossible de récupérer les recommandations pour le moment.'];
        }
    }
}
