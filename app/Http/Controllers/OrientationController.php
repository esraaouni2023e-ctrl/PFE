<?php

namespace App\Http\Controllers;

use App\Models\Specialite;
use App\Models\Formation;
use Illuminate\Http\Request;

class OrientationController extends Controller
{
    public function index(Request $request)
    {
        $domaine  = $request->get('domaine', 'Toutes');
        $search   = $request->get('search', '');
        $niveau   = $request->get('niveau', '');

        $specialites = Specialite::with('formations')->get();

        $formationsQuery = Formation::with('specialite');

        if ($domaine !== 'Toutes') {
            $formationsQuery->whereHas('specialite', fn($q) => $q->where('domaine', $domaine));
        }

        if ($search) {
            $formationsQuery->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('etablissement', 'like', "%{$search}%")
                  ->orWhere('secteur', 'like', "%{$search}%")
                  ->orWhereHas('specialite', fn($sq) => $sq->where('nom', 'like', "%{$search}%"));
            });
        }

        if ($niveau) {
            $formationsQuery->where('niveau', $niveau);
        }

        $formations = $formationsQuery->orderByDesc('score_matching')->paginate(12)->withQueryString();

        $domaines = ['Toutes', 'Technologie', 'Santé', 'Sciences', 'Gestion', 'Arts', 'Droit', 'Éducation'];
        $niveaux  = ['Licence', 'Master', 'Ingénierie', 'Doctorat'];

        return view('student.orientation', compact(
            'specialites', 'formations', 'domaine', 'search', 'niveau', 'domaines', 'niveaux'
        ));
    }

    public function show($id)
    {
        $formation = Formation::with('specialite')->findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json($formation);
        }

        return response()->json($formation);
    }
}
