<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OllamaService
{
    private string $baseUrl = 'http://localhost:11434/api/chat';
    private string $model = 'llama3';

    /**
     * Analyze a document text to extract skills.
     */
    public function analyzePortfolioItem(string $text)
    {
        $prompt = "Tu es un conseiller d'orientation IA. Voici le contenu d'un projet ou certificat d'un étudiant. Fais un bref résumé (2 phrases) et liste les compétences clés acquises sous forme de tableau JSON. Réponds uniquement avec le JSON suivant le format {\"summary\": \"...\", \"skills\": [\"...\", \"...\"]}. Texte : " . substr($text, 0, 2000);

        try {
            $response = Http::timeout(60)->post($this->baseUrl, [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => 'Tu dois toujours répondre en JSON valide.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'stream' => false,
                'format' => 'json'
            ]);

            if ($response->successful()) {
                $content = $response->json('message.content');
                return json_decode($content, true);
            }
        } catch (\Exception $e) {
            Log::error('Ollama analysis failed: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Generate a career roadmap based on target job and profile.
     */
    public function generateRoadmap(string $targetJob, $profileData)
    {
        $prompt = "Génère une roadmap académique et professionnelle détaillée pour devenir '$targetJob'. Prends en compte ces infos de l'étudiant : " . json_encode($profileData) . ". Réponds UNIQUEMENT en JSON avec un tableau d'étapes. Chaque étape doit avoir : 'title' (ex: Licence), 'duration' (ex: 3 ans), 'description' (ce qu'il faut apprendre).";

        try {
            $response = Http::timeout(60)->post($this->baseUrl, [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => 'Tu dois toujours répondre en JSON.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'stream' => false,
                'format' => 'json'
            ]);

            if ($response->successful()) {
                $content = $response->json('message.content');
                return json_decode($content, true);
            }
        } catch (\Exception $e) {
            Log::error('Ollama roadmap generation failed: ' . $e->getMessage());
        }

        return null;
    }
}
