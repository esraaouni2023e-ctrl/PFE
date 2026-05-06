<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\WhatIfRequest;
use App\Models\SimulationHistory;
use App\Services\ScoreFGService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * WhatIfController — Simulateur What-If pour le calcul du Score FG.
 *
 * Permet à l'étudiant de simuler différents scénarios de notes
 * et de voir immédiatement les formations accessibles.
 */
class WhatIfController extends Controller
{
    public function __construct(private readonly ScoreFGService $scoreFgService) {}

    /**
     * Affiche la page du simulateur.
     */
    public function index(): \Illuminate\View\View
    {
        $user             = auth()->user();
        $profile          = $user->profile;
        $historiqueRecent = $user->simulationHistory()
                                 ->latest()
                                 ->limit(5)
                                 ->get();

        return view('student.whatif.index', [
            'sections'        => $this->scoreFgService->getSections(),
            'profile'         => $profile,
            'historiqueRecent' => $historiqueRecent,
        ]);
    }

    /**
     * Calcule le score FG et retourne les résultats (AJAX JSON).
     */
    public function calculer(WhatIfRequest $request): JsonResponse
    {
        $section = $request->input('section_bac');
        $mg      = (float) $request->input('moyenne_generale');
        $notes   = $request->input('notes', []);

        try {
            $scoreFg = $this->scoreFgService->calculer($section, $mg, $notes);
            $formations = $this->scoreFgService->getFormationsAccessibles($scoreFg, 8);

            // Sauvegarde dans l'historique
            $simulation = SimulationHistory::create([
                'user_id'          => auth()->id(),
                'section_bac'      => $section,
                'moyenne_generale' => $mg,
                'notes_matieres'   => $notes,
                'score_fg'         => $scoreFg,
                'label'            => $request->input('label'),
                'formations_accessibles' => $formations->map(fn($f) => [
                    'id'   => $f->id,
                    'nom'  => $f->nom,
                    'icon' => $f->icon,
                    'etablissement' => $f->etablissement,
                    'niveau' => $f->niveau,
                ])->toArray(),
            ]);

            return response()->json([
                'success'    => true,
                'score_fg'   => $scoreFg,
                'niveau'     => $simulation->niveau_score,
                'simulation_id' => $simulation->id,
                'formations' => $formations->map(fn($f) => [
                    'id'            => $f->id,
                    'nom'           => $f->nom,
                    'icon'          => $f->icon,
                    'etablissement' => $f->etablissement,
                    'ville'         => $f->ville,
                    'niveau'        => $f->niveau,
                    'duree'         => $f->duree,
                    'score_matching'=> $f->score_matching,
                    'salaire_min'   => $f->salaire_min,
                    'salaire_max'   => $f->salaire_max,
                    'domaine'       => $f->specialite?->domaine ?? '',
                ]),
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur de calcul.'], 500);
        }
    }

    /**
     * Retourne les matières requises pour une section (AJAX).
     */
    public function getMatieres(Request $request): JsonResponse
    {
        $section = $request->input('section');
        $matieres = $this->scoreFgService->getMatieresSection($section);

        return response()->json(['matieres' => $matieres]);
    }

    /**
     * Historique complet des simulations de l'étudiant.
     */
    public function historique(): \Illuminate\View\View
    {
        $historique = auth()->user()
                           ->simulationHistory()
                           ->latest()
                           ->paginate(10);

        return view('student.whatif.historique', compact('historique'));
    }

    /**
     * Supprime une simulation de l'historique.
     */
    public function destroy(SimulationHistory $simulation): \Illuminate\Http\RedirectResponse
    {
        // Vérification propriétaire
        abort_if($simulation->user_id !== auth()->id(), 403);

        $simulation->delete();

        return back()->with('success', 'Simulation supprimée.');
    }
}
