<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\UpdateProfileAcademiqueRequest;
use App\Models\Profile;
use App\Services\ScoreFGService;
use Illuminate\Http\Request;

/**
 * StudentProfileController — Gestion du profil académique étudiant.
 *
 * Séparé du ProfileController générique pour respecter le SRP.
 */
class StudentProfileController extends Controller
{
    public function __construct(private readonly ScoreFGService $scoreFgService) {}

    /**
     * Affiche le profil académique de l'étudiant.
     */
    public function show(): \Illuminate\View\View
    {
        $user    = auth()->user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

        $sections    = $this->scoreFgService->getSections();
        $gouvernorats = $this->getGouvernorats();

        return view('student.profil', compact('user', 'profile', 'sections', 'gouvernorats'));
    }

    /**
     * Met à jour le profil académique.
     */
    public function update(UpdateProfileAcademiqueRequest $request): \Illuminate\Http\RedirectResponse
    {
        $user    = auth()->user();
        $profile = $user->profile ?? Profile::create(['user_id' => $user->id]);

        $profile->update([
            'section_bac'     => $request->input('section_bac'),
            'moyenne_generale' => $request->input('moyenne_generale'),
            'annee_bac'       => $request->input('annee_bac'),
            'gouvernorat'     => $request->input('gouvernorat'),
            'notes_matieres'  => $request->input('notes_matieres'),
            'interests'       => $request->input('interests'),
            'skills'          => $request->input('skills'),
        ]);

        // Recalculer automatiquement le Score FG
        try {
            $scoreFg = $this->scoreFgService->calculer(
                $request->input('section_bac'),
                (float) $request->input('moyenne_generale'),
                $request->input('notes_matieres', [])
            );

            $profile->update([
                'score_fg'            => $scoreFg,
                'score_fg_updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Score FG non recalculé — non bloquant
        }

        if (session('from_pipeline')) {
            session()->forget('from_pipeline');
            return redirect()->route('student.pipeline')
                             ->with('success', '✅ Score FG calculé ! Étape 2 : Le test RIASEC.');
        }

        return redirect()->route('student.profil')
                         ->with('success', '✅ Profil académique mis à jour avec succès !');
    }

    /**
     * Retourne la liste des 24 gouvernorats de Tunisie.
     */
    private function getGouvernorats(): array
    {
        return [
            'Ariana', 'Béja', 'Ben Arous', 'Bizerte', 'Gabès',
            'Gafsa', 'Jendouba', 'Kairouan', 'Kasserine', 'Kébili',
            'Le Kef', 'Mahdia', 'La Manouba', 'Médenine', 'Monastir',
            'Nabeul', 'Sfax', 'Sidi Bouzid', 'Siliana', 'Sousse',
            'Tataouine', 'Tozeur', 'Tunis', 'Zaghouan',
        ];
    }
}
