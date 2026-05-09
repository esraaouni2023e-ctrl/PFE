<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\FiliereImport;
use App\Models\Filiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class FiliereImportController extends Controller
{
    /** Catégories disponibles (même liste que FILE_MAP de la commande). */
    private const CATEGORIES = ['INFO', 'TECH', 'ECO', 'EXP', 'SPORT', 'MAT', 'LET'];

    // ══════════════════════════════════════════════════════════════════════
    // GET /admin/filieres/import
    // ══════════════════════════════════════════════════════════════════════

    public function index()
    {
        $stats = Filiere::selectRaw('categorie, COUNT(*) as total')
            ->groupBy('categorie')
            ->orderBy('categorie')
            ->pluck('total', 'categorie');

        return view('admin.filieres.import', [
            'categories' => self::CATEGORIES,
            'stats'      => $stats,
            'totalRows'  => Filiere::count(),
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════
    // POST /admin/filieres/import
    // ══════════════════════════════════════════════════════════════════════

    public function store(Request $request)
    {
        $request->validate([
            'fichier'   => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:20480'],
            'categorie' => ['required', 'string', 'in:' . implode(',', self::CATEGORIES)],
        ]);

        // Stockage temporaire sécurisé
        $path = $request->file('fichier')->store('excels/tmp');

        try {
            $import = new FiliereImport($request->categorie);
            Excel::import($import, Storage::path($path));

            // Nettoyage du fichier temporaire
            Storage::delete($path);

            return back()->with('success', sprintf(
                'Import terminé — %d insérées, %d mises à jour, %d ignorées.',
                $import->inserted,
                $import->updated,
                $import->skipped
            ));

        } catch (\Exception $e) {
            Storage::delete($path);

            return back()->withErrors([
                'fichier' => 'Erreur lors de l\'import : ' . $e->getMessage(),
            ]);
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    // DELETE /admin/filieres/import/{categorie}
    // Vider toutes les filières d'une catégorie
    // ══════════════════════════════════════════════════════════════════════

    public function destroy(string $categorie)
    {
        abort_unless(in_array(strtoupper($categorie), self::CATEGORIES), 404);

        $deleted = Filiere::where('categorie', strtoupper($categorie))->delete();

        return back()->with('success', "{$deleted} filières supprimées de la catégorie {$categorie}.");
    }
}
