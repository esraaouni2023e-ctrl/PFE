<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * ComparateurController — Comparateur interactif de filières.
 *
 * Permet de comparer 2 à 4 filières côte-à-côte via radar Chart.js.
 */
class ComparateurController extends Controller
{
    /**
     * Affiche la page du comparateur.
     */
    public function index(): \Illuminate\View\View
    {
        $formations = Formation::with('specialite')
                               ->orderBy('nom')
                               ->get(['id', 'nom', 'etablissement', 'niveau', 'specialite_id', 'icon']);

        return view('student.comparateur.index', compact('formations'));
    }

    /**
     * Retourne les données de comparaison pour un ensemble d'IDs (AJAX).
     */
    public function comparer(Request $request): JsonResponse
    {
        $ids = $request->validate([
            'ids'   => ['required', 'array', 'min:2', 'max:4'],
            'ids.*' => ['integer', 'exists:formations,id'],
        ])['ids'];

        $formations = Formation::with('specialite')
                               ->whereIn('id', $ids)
                               ->get();

        $data = $formations->map(function (Formation $f) {
            // Normalisation des métriques sur 100 pour le radar
            $salaireMax     = 8000; // référence plafond
            $dureeMax       = 60;   // mois max

            // Conversion durée en mois
            $dureeMois = $this->parseDureeMois($f->duree);

            return [
                'id'             => $f->id,
                'nom'            => $f->nom,
                'icon'           => $f->icon,
                'etablissement'  => $f->etablissement,
                'ville'          => $f->ville,
                'niveau'         => $f->niveau,
                'duree'          => $f->duree,
                'domaine'        => $f->specialite?->domaine ?? 'N/A',
                'icon_spec'      => $f->specialite?->icon ?? '🎓',
                'salaire_min'    => $f->salaire_min,
                'salaire_max'    => $f->salaire_max,
                'description'    => $f->description,
                'debouches'      => $f->debouches,
                // Scores normalisés pour le radar (0-100)
                'radar' => [
                    'matching'    => (int) $f->score_matching,
                    'salaire'     => (int) min(100, ($f->salaire_max / $salaireMax) * 100),
                    'rapidite'    => (int) max(0, 100 - (($dureeMois / $dureeMax) * 100)),
                    'insertion'   => $this->getInsertionScore($f->niveau),
                    'accessibilite'=> (int) $f->score_matching,
                ],
            ];
        });

        return response()->json(['success' => true, 'formations' => $data]);
    }

    /**
     * Parse la durée textuelle en mois (ex: "3 ans" → 36).
     */
    private function parseDureeMois(string $duree): int
    {
        if (preg_match('/(\d+)\s*ans?/i', $duree, $m)) return (int)$m[1] * 12;
        if (preg_match('/(\d+)\s*mois/i', $duree, $m)) return (int)$m[1];
        return 36; // défaut
    }

    /**
     * Score d'insertion professionnelle estimé par niveau.
     */
    private function getInsertionScore(string $niveau): int
    {
        return match ($niveau) {
            'Doctorat'   => 95,
            'Ingénierie' => 90,
            'Master'     => 82,
            'Licence'    => 72,
            'BTS'        => 78,
            default      => 70,
        };
    }
}
