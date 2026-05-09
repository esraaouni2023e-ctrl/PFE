<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\OrientationVoeu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * VoeuxController — Gestion de la wishlist d'orientation.
 *
 * L'étudiant peut ajouter/retirer des filières et les ordonner par priorité.
 */
class VoeuxController extends Controller
{
    /**
     * Liste des vœux de l'étudiant, ordonnés par priorité.
     */
    public function index(): \Illuminate\View\View
    {
        $voeux = auth()->user()
                       ->orientationVoeux()
                       ->with(['formation.specialite'])
                       ->ordonnes()
                       ->get();

        return view('student.voeux.index', compact('voeux'));
    }

    /**
     * Bascule (toggle) un vœu : l'ajoute s'il n'existe pas, le supprime sinon.
     * Retourne JSON pour appel AJAX depuis les cards orientation.
     */
    public function toggle(Formation $formation): JsonResponse
    {
        $user = auth()->user();

        $voeu = OrientationVoeu::where('user_id', $user->id)
                               ->where('formation_id', $formation->id)
                               ->first();

        if ($voeu) {
            $voeu->delete();
            return response()->json([
                'action'  => 'removed',
                'message' => '❌ Filière retirée de vos vœux.',
            ]);
        }

        // Calcul automatique de la prochaine priorité
        $prochainePriorite = OrientationVoeu::where('user_id', $user->id)
                                            ->max('priorite') + 1;

        OrientationVoeu::create([
            'user_id'     => $user->id,
            'formation_id'=> $formation->id,
            'priorite'    => $prochainePriorite,
        ]);

        return response()->json([
            'action'   => 'added',
            'priorite' => $prochainePriorite,
            'message'  => '❤️ Filière ajoutée à vos vœux.',
        ]);
    }

    /**
     * Met à jour l'ordre de priorité (drag & drop).
     * Reçoit un tableau JSON d'IDs dans le nouvel ordre.
     */
    public function reordonner(Request $request): JsonResponse
    {
        $ids = $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer'],
        ])['ids'];

        $userId = auth()->id();

        foreach ($ids as $priorite => $voeuId) {
            OrientationVoeu::where('id', $voeuId)
                           ->where('user_id', $userId) // sécurité : propriétaire seulement
                           ->update(['priorite' => $priorite + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Priorités mises à jour.']);
    }

    /**
     * Met à jour les notes personnelles d'un vœu.
     */
    public function update(Request $request, OrientationVoeu $voeu): JsonResponse
    {
        abort_if($voeu->user_id !== auth()->id(), 403);

        $voeu->update([
            'notes_perso'  => $request->input('notes_perso', ''),
            'est_confirme' => $request->boolean('est_confirme'),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Supprime un vœu.
     */
    public function destroy(OrientationVoeu $voeu): JsonResponse
    {
        abort_if($voeu->user_id !== auth()->id(), 403);
        $voeu->delete();

        return response()->json(['success' => true, 'message' => 'Vœu supprimé.']);
    }
}
