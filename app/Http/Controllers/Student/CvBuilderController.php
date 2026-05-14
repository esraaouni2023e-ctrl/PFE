<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CvProfile;
use App\Models\CvExperience;
use App\Models\CvEducation;
use App\Models\CvSkill;
use App\Models\CvLanguage;
use App\Services\Cv\PdfGeneratorService;
use App\Services\Cv\DocxGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CvBuilderController extends Controller
{
    /**
     * Liste des CVs de l'utilisateur connecté.
     */
    public function index()
    {
        $cvProfiles = Auth::user()
            ->cvProfiles()
            ->withCount(['experiences', 'educations', 'skills', 'languages'])
            ->latest()
            ->get();

        return view('student.cv.index', compact('cvProfiles'));
    }

    /**
     * Formulaire de création d'un nouveau CV.
     */
    public function create()
    {
        return view('student.cv.builder', [
            'cvProfile' => null,
            'templates' => $this->availableTemplates(),
        ]);
    }

    /**
     * Sauvegarde d'un nouveau CV complet (profil + relations).
     */
    public function store(Request $request)
    {
        $validated = $this->validateCvData($request);

        $cvProfile = Auth::user()->cvProfiles()->create([
            'title'         => $validated['title'],
            'template_name' => $validated['template_name'] ?? 'modern',
            'summary'       => $validated['summary'] ?? null,
            'target_job'    => $validated['target_job'] ?? null,
        ]);

        $this->syncRelations($cvProfile, $validated);

        return redirect()
            ->route('student.cv.index')
            ->with('success', 'CV "' . $cvProfile->title . '" créé avec succès ! Téléchargez-le en PDF ou DOCX.');
    }

    /**
     * Formulaire d'édition d'un CV existant.
     */
    public function edit(CvProfile $cvProfile)
    {
        $this->authorizeOwnership($cvProfile);

        $cvProfile->load(['experiences', 'educations', 'skills', 'languages']);

        return view('student.cv.builder', [
            'cvProfile' => $cvProfile,
            'templates' => $this->availableTemplates(),
        ]);
    }

    /**
     * Mise à jour d'un CV existant.
     */
    public function update(Request $request, CvProfile $cvProfile)
    {
        $this->authorizeOwnership($cvProfile);

        $validated = $this->validateCvData($request);

        $cvProfile->update([
            'title'         => $validated['title'],
            'template_name' => $validated['template_name'] ?? 'modern',
            'summary'       => $validated['summary'] ?? null,
            'target_job'    => $validated['target_job'] ?? null,
        ]);

        // Supprimer les anciennes relations et recréer
        $cvProfile->experiences()->delete();
        $cvProfile->educations()->delete();
        $cvProfile->skills()->delete();
        $cvProfile->languages()->delete();

        $this->syncRelations($cvProfile, $validated);

        return redirect()
            ->route('student.cv.edit', $cvProfile)
            ->with('success', 'CV mis à jour avec succès !');
    }

    /**
     * Suppression d'un CV.
     */
    public function destroy(CvProfile $cvProfile)
    {
        $this->authorizeOwnership($cvProfile);

        $cvProfile->delete();

        return redirect()
            ->route('student.cv.index')
            ->with('success', 'CV supprimé.');
    }

    /**
     * Duplication d'un CV existant.
     */
    public function duplicate(CvProfile $cvProfile)
    {
        $this->authorizeOwnership($cvProfile);

        $cvProfile->load(['experiences', 'educations', 'skills', 'languages']);

        $newProfile = $cvProfile->replicate();
        $newProfile->title = $cvProfile->title . ' (copie)';
        $newProfile->save();

        foreach ($cvProfile->experiences as $exp) {
            $newProfile->experiences()->create($exp->only([
                'company', 'position', 'start_date', 'end_date',
                'is_current', 'description', 'order',
            ]));
        }

        foreach ($cvProfile->educations as $edu) {
            $newProfile->educations()->create($edu->only([
                'institution', 'degree', 'field_of_study',
                'start_date', 'end_date', 'is_current', 'description', 'order',
            ]));
        }

        foreach ($cvProfile->skills as $skill) {
            $newProfile->skills()->create($skill->only(['name', 'level', 'order']));
        }

        foreach ($cvProfile->languages as $lang) {
            $newProfile->languages()->create($lang->only(['name', 'level', 'order']));
        }

        return redirect()
            ->route('student.cv.edit', $newProfile)
            ->with('success', 'CV dupliqué avec succès !');
    }

    /**
     * Téléchargement en PDF.
     */
    public function downloadPdf(CvProfile $cvProfile, PdfGeneratorService $pdfService)
    {
        $this->authorizeOwnership($cvProfile);

        $cvProfile->load(['experiences', 'educations', 'skills', 'languages']);

        return $pdfService->generate($cvProfile);
    }

    /**
     * Téléchargement en DOCX.
     */
    public function downloadDocx(CvProfile $cvProfile, DocxGeneratorService $docxService)
    {
        $this->authorizeOwnership($cvProfile);

        $cvProfile->load(['experiences', 'educations', 'skills', 'languages']);

        return $docxService->generate($cvProfile);
    }

    /**
     * Prévisualisation HTML (pour l'iframe live).
     */
    public function preview(CvProfile $cvProfile)
    {
        $this->authorizeOwnership($cvProfile);

        $cvProfile->load(['experiences', 'educations', 'skills', 'languages']);

        $template = 'cv_templates.' . $cvProfile->template_name . '_pdf';

        return view($template, [
            'cv'   => $cvProfile,
            'user' => $cvProfile->user,
        ]);
    }

    // ─── Private Helpers ─────────────────────────────────────────────────

    /**
     * Vérifier que le CV appartient à l'utilisateur connecté.
     */
    private function authorizeOwnership(CvProfile $cvProfile): void
    {
        abort_unless($cvProfile->user_id === Auth::id(), 403);
    }

    /**
     * Templates disponibles pour le sélecteur.
     */
    private function availableTemplates(): array
    {
        return [
            'modern'     => ['name' => 'Moderne',     'icon' => '🎨', 'desc' => 'Design épuré avec accents de couleur'],
            'classic'    => ['name' => 'Classique',    'icon' => '📋', 'desc' => 'Format traditionnel, sobre et élégant'],
            'minimal'    => ['name' => 'Minimaliste',  'icon' => '✨', 'desc' => 'Ultra-simple, focus sur le contenu'],
        ];
    }

    /**
     * Validation des données du formulaire CV.
     */
    private function validateCvData(Request $request): array
    {
        return $request->validate([
            'title'                       => 'required|string|max:255',
            'template_name'               => 'nullable|string|in:modern,classic,minimal',
            'summary'                     => 'nullable|string|max:2000',
            'target_job'                  => 'nullable|string|max:255',

            // Expériences
            'experiences'                 => 'nullable|array|max:20',
            'experiences.*.company'       => 'required_with:experiences|string|max:255',
            'experiences.*.position'      => 'required_with:experiences|string|max:255',
            'experiences.*.start_date'    => 'required_with:experiences|date',
            'experiences.*.end_date'      => 'nullable|date',
            'experiences.*.is_current'    => 'nullable|boolean',
            'experiences.*.description'   => 'nullable|string|max:3000',

            // Formations
            'educations'                  => 'nullable|array|max:10',
            'educations.*.institution'    => 'required_with:educations|string|max:255',
            'educations.*.degree'         => 'required_with:educations|string|max:255',
            'educations.*.field_of_study' => 'nullable|string|max:255',
            'educations.*.start_date'     => 'required_with:educations|date',
            'educations.*.end_date'       => 'nullable|date',
            'educations.*.is_current'     => 'nullable|boolean',
            'educations.*.description'    => 'nullable|string|max:2000',

            // Compétences
            'skills'                      => 'nullable|array|max:30',
            'skills.*.name'              => 'required_with:skills|string|max:100',
            'skills.*.level'             => 'nullable|string|in:Débutant,Intermédiaire,Avancé,Expert',

            // Langues
            'languages'                   => 'nullable|array|max:10',
            'languages.*.name'           => 'required_with:languages|string|max:100',
            'languages.*.level'          => 'nullable|string|max:50',
        ], [
            'title.required'                    => 'Le titre du CV est obligatoire.',
            'experiences.*.company.required_with' => 'Le nom de l\'entreprise est requis pour chaque expérience.',
            'experiences.*.position.required_with'=> 'Le poste est requis pour chaque expérience.',
            'experiences.*.start_date.required_with' => 'La date de début est requise pour chaque expérience.',
            'experiences.*.start_date.date'     => 'La date de début d\'expérience n\'est pas valide.',
            'experiences.*.end_date.date'       => 'La date de fin d\'expérience n\'est pas valide.',
            'educations.*.institution.required_with' => 'L\'établissement est requis pour chaque formation.',
            'educations.*.degree.required_with' => 'Le diplôme est requis pour chaque formation.',
            'educations.*.start_date.required_with' => 'La date de début est requise pour chaque formation.',
            'educations.*.start_date.date'      => 'La date de début de formation n\'est pas valide.',
            'educations.*.end_date.date'        => 'La date de fin de formation n\'est pas valide.',
            'skills.*.name.required_with'       => 'Le nom de la compétence est requis.',
            'skills.*.level.in'                 => 'Le niveau de compétence doit être : Débutant, Intermédiaire, Avancé ou Expert.',
            'languages.*.name.required_with'    => 'Le nom de la langue est requis.',
        ]);
    }

    /**
     * Créer les relations (expériences, formations, compétences, langues).
     */
    private function syncRelations(CvProfile $cvProfile, array $data): void
    {
        foreach ($data['experiences'] ?? [] as $i => $exp) {
            $cvProfile->experiences()->create(array_merge($exp, [
                'is_current' => $exp['is_current'] ?? false,
                'order'      => $i,
            ]));
        }

        foreach ($data['educations'] ?? [] as $i => $edu) {
            $cvProfile->educations()->create(array_merge($edu, [
                'is_current' => $edu['is_current'] ?? false,
                'order'      => $i,
            ]));
        }

        foreach ($data['skills'] ?? [] as $i => $skill) {
            $cvProfile->skills()->create(array_merge($skill, ['order' => $i]));
        }

        foreach ($data['languages'] ?? [] as $i => $lang) {
            $cvProfile->languages()->create(array_merge($lang, ['order' => $i]));
        }
    }
}
