<?php

namespace App\Http\Controllers;

use App\Models\Specialite;
use App\Models\Formation;
use App\Models\Filiere;
use Illuminate\Http\Request;

class OrientationController extends Controller
{
    public function index(Request $request)
    {
        $domaine        = $request->get('domaine', '');
        $etablissement  = $request->get('etablissement', '');
        $recherche      = $request->get('recherche', '');
        $niveau         = $request->get('niveau', '');

        $query = Filiere::query();

        if ($request->filled('recherche')) {
            $q = $request->recherche;
            $query->where(function($q2) use ($q) {
                $q2->where('nom_filiere', 'like', "%$q%")
                   ->orWhere('etablissement', 'like', "%$q%")
                   ->orWhere('code_filiere', 'like', "%$q%");
            });
        }

        if ($request->filled('domaine') && $request->domaine !== 'Toutes') {
            $query->where('domaine', $request->domaine);
        }

        if ($request->filled('etablissement')) {
            $query->where('etablissement', $request->etablissement);
        }

        if ($request->filled('niveau')) {
            $query->where('nom_filiere', 'like', '%' . $request->niveau . '%');
        }

        $filieres = $query->paginate(15)->withQueryString();

        $etablissements = Filiere::select('etablissement')
                                 ->distinct()
                                 ->orderBy('etablissement')
                                 ->pluck('etablissement');

        $domaines = [
            'Économie et Gestion',
            'Sciences Expérimentales',
            'Mathématiques et Appliquées',
            'Informatique',
            'Technologie',
            'Sport',
            'Lettres et Sciences Humaines'
        ];

        $niveaux = ['Licence', 'Master', 'Ingénierie', 'Doctorat'];
        $specialites = Specialite::all(); 

        return view('student.orientation', compact(
            'filieres', 'etablissements', 'domaines', 'niveaux', 'specialites',
            'domaine', 'etablissement', 'recherche', 'niveau'
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
