<?php

namespace App\Http\Controllers;

use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RecommendationController extends Controller
{
    public function __construct(protected RecommendationService $recommendationService)
    {
    }

    /**
     * GET /recommendations
     * Affiche le formulaire de saisie.
     */
    public function showForm(): View
    {
        return view('recommendations.form');
    }

    /**
     * POST /recommendations
     * Valide les données, appelle l'API Python et affiche les résultats.
     */
    public function getRecommendations(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'score_fg'     => ['required', 'numeric', 'min:0', 'max:200'],
            'riasec_input' => ['required', 'string', 'size:3', 'regex:/^[A-Za-z]{3}$/'],
        ], [
            'score_fg.required'     => 'Le score du baccalauréat est obligatoire.',
            'score_fg.numeric'      => 'Le score doit être un nombre.',
            'score_fg.min'          => 'Le score ne peut pas être inférieur à 0.',
            'score_fg.max'          => 'Le score ne peut pas dépasser 200.',
            'riasec_input.required' => 'Le code RIASEC est obligatoire.',
            'riasec_input.size'     => 'Le code RIASEC doit contenir exactement 3 lettres (ex: ISA, RIC).',
            'riasec_input.regex'    => 'Le code RIASEC ne doit contenir que des lettres (ex: ISA, RIC).',
        ]);

        $result = $this->recommendationService->getRecommendations(
            (float) $validated['score_fg'],
            $validated['riasec_input']
        );

        if (! $result['success']) {
            return back()
                ->withInput()
                ->with('error', $result['error']);
        }

        $data = $result['data'];

        if (empty($data['recommendations'])) {
            return back()
                ->withInput()
                ->with('info', 'Aucune filière trouvée pour ce profil. Essayez avec un autre code RIASEC ou un score différent.');
        }

        return view('recommendations.results', [
            'recommendations'           => $data['recommendations'],
            'riasec'                    => $data['riasec']    ?? $validated['riasec_input'],
            'scoreFg'                   => $data['score_fg']  ?? $validated['score_fg'],
            'totalFilieresAccessibles'  => $data['total_filieres_accessibles'] ?? 0,
        ]);
    }
}
