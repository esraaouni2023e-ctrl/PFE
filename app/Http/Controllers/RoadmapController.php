<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CareerRoadmap;
use App\Services\OllamaService;

class RoadmapController extends Controller
{
    protected OllamaService $ollama;

    public function __construct(OllamaService $ollama)
    {
        $this->ollama = $ollama;
    }

    public function generate(Request $request)
    {
        $request->validate([
            'target_job' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $targetJob = $request->target_job;
        
        // Simuler les données du profil pour la prompt (notes, etc.)
        $profileData = [
            'skills' => $user->profile->skills ?? ['Logique' => 70, 'Créativité' => 80],
            'interests' => $user->profile->interests ?? ['Technologie', 'Design'],
        ];

        // Lancer la génération via Ollama
        $steps = $this->ollama->generateRoadmap($targetJob, $profileData);

        if (!$steps) {
            // Fallback en cas d'erreur IA pour le MVP
            $steps = [
                ['title' => 'Licence', 'duration' => '3 ans', 'description' => 'Bases fondamentales en ' . $targetJob],
                ['title' => 'Master', 'duration' => '2 ans', 'description' => 'Spécialisation avancée'],
                ['title' => 'Premier Poste', 'duration' => 'Junior', 'description' => 'Intégration sur le marché du travail en tant que ' . $targetJob],
            ];
        }

        CareerRoadmap::create([
            'user_id' => $user->id,
            'target_job' => $targetJob,
            'steps' => $steps,
        ]);

        return redirect()->back()->with('success', 'Roadmap de carrière générée avec succès !');
    }
}
