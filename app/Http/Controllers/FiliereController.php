<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use Illuminate\Http\Request;

class FiliereController extends Controller
{
    public function search(Request $request)
    {
        $domainesValides = [
            'Économie et Gestion', 
            'Sciences Expérimentales',
            'Mathématiques et Appliquées', 
            'Informatique',
            'Technologie', 
            'Sport', 
            'Lettres et Sciences Humaines'
        ];

        $query = Filiere::query();

        if ($request->filled('recherche')) {
            $q = $request->recherche;
            $query->where(function($q2) use ($q) {
                $q2->where('nom_filiere', 'like', "%$q%")
                   ->orWhere('etablissement', 'like', "%$q%")
                   ->orWhere('code_filiere', 'like', "%$q%");
            });
        }

        if ($request->filled('domaine') && in_array($request->domaine, $domainesValides)) {
            $query->where('domaine', $request->domaine);
        }

        if ($request->filled('etablissement')) {
            $query->where('etablissement', 'like', '%' . $request->etablissement . '%');
        }

        $filieres = $query->paginate(15)->withQueryString();
        
        $etablissements = Filiere::select('etablissement')
            ->whereNotNull('etablissement')
            ->distinct()
            ->orderBy('etablissement')
            ->pluck('etablissement');
            
        $domaines = $domainesValides;

        return view('recherche', compact('filieres', 'etablissements', 'domaines'));
    }
}
