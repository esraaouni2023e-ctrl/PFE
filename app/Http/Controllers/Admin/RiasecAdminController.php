<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnswerRiasec;
use App\Models\ProfileRiasec;
use App\Models\QuestionRiasec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * RiasecAdminController — CRUD Questions RIASEC + Dashboard statistiques.
 */
class RiasecAdminController extends Controller
{
    // ══════════════════════════════════════════════════════════════════════
    // DASHBOARD  GET /admin/riasec
    // ══════════════════════════════════════════════════════════════════════

    public function dashboard()
    {
        // ── Statistiques générales ────────────────────────────────────────
        $totalQuestions   = QuestionRiasec::count();
        $activeQuestions  = QuestionRiasec::where('actif', true)->count();
        $totalTests       = ProfileRiasec::count();
        $completedTests   = ProfileRiasec::where('statut', ProfileRiasec::STATUT_COMPLET)->count();
        $inProgressTests  = ProfileRiasec::where('statut', ProfileRiasec::STATUT_EN_COURS)->count();
        $totalAnswers     = AnswerRiasec::count();

        // ── Cohérence moyenne ─────────────────────────────────────────────
        $avgCoherence = ProfileRiasec::where('statut', ProfileRiasec::STATUT_COMPLET)
            ->whereNotNull('score_coherence')
            ->avg('score_coherence');

        // ── Répartition par code Holland (top 10) ─────────────────────────
        $hollandDistrib = ProfileRiasec::where('statut', ProfileRiasec::STATUT_COMPLET)
            ->select('code_holland', DB::raw('COUNT(*) as total'))
            ->groupBy('code_holland')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // ── Scores moyens par dimension ───────────────────────────────────
        $avgScores = ProfileRiasec::where('statut', ProfileRiasec::STATUT_COMPLET)
            ->selectRaw('
                ROUND(AVG(score_r),1) as r,
                ROUND(AVG(score_i),1) as i,
                ROUND(AVG(score_a),1) as a,
                ROUND(AVG(score_s),1) as s,
                ROUND(AVG(score_e),1) as e,
                ROUND(AVG(score_c),1) as c
            ')
            ->first();

        // ── Tests complétés par jour (30 derniers jours) ──────────────────
        $testsPerDay = ProfileRiasec::where('statut', ProfileRiasec::STATUT_COMPLET)
            ->where('complete_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(complete_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date');

        // ── Répartition par catégorie de questions ────────────────────────
        $questionsByCategorie = QuestionRiasec::select('categorie', DB::raw('count(*) as total'))
            ->groupBy('categorie')
            ->pluck('total', 'categorie');

        // ── Derniers tests ────────────────────────────────────────────────
        $recentTests = ProfileRiasec::with('user')
            ->where('statut', ProfileRiasec::STATUT_COMPLET)
            ->orderByDesc('complete_at')
            ->limit(8)
            ->get();

        return view('admin.riasec.dashboard', compact(
            'totalQuestions', 'activeQuestions', 'totalTests', 'completedTests',
            'inProgressTests', 'totalAnswers', 'avgCoherence', 'hollandDistrib',
            'avgScores', 'testsPerDay', 'questionsByCategorie', 'recentTests'
        ));
    }

    // ══════════════════════════════════════════════════════════════════════
    // QUESTIONS LIST  GET /admin/riasec/questions
    // ══════════════════════════════════════════════════════════════════════

    public function index(Request $request)
    {
        $query = QuestionRiasec::query();

        // Filtres
        if ($request->filled('dimension')) {
            $query->where('dimension', $request->dimension);
        }
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }
        if ($request->filled('actif')) {
            $query->where('actif', (bool) $request->actif);
        }
        if ($request->filled('q')) {
            $query->where('texte_fr', 'like', '%' . $request->q . '%');
        }

        $questions = $query->orderBy('dimension')->orderBy('ordre')->paginate(20)->withQueryString();

        $stats = [
            'total'    => QuestionRiasec::count(),
            'actives'  => QuestionRiasec::where('actif', true)->count(),
            'byDim'    => QuestionRiasec::select('dimension', DB::raw('count(*) as n'))->groupBy('dimension')->pluck('n','dimension'),
        ];

        return view('admin.riasec.questions.index', compact('questions', 'stats'));
    }

    // ══════════════════════════════════════════════════════════════════════
    // CREATE  GET/POST /admin/riasec/questions/create
    // ══════════════════════════════════════════════════════════════════════

    public function create()
    {
        return view('admin.riasec.questions.form', ['question' => null]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateQuestion($request);
        QuestionRiasec::create($validated);

        return redirect()
            ->route('admin.riasec.questions.index')
            ->with('success', 'Question créée avec succès.');
    }

    // ══════════════════════════════════════════════════════════════════════
    // EDIT  GET/PUT /admin/riasec/questions/{id}
    // ══════════════════════════════════════════════════════════════════════

    public function edit(QuestionRiasec $question)
    {
        return view('admin.riasec.questions.form', compact('question'));
    }

    public function update(Request $request, QuestionRiasec $question)
    {
        $validated = $this->validateQuestion($request);
        $question->update($validated);

        return redirect()
            ->route('admin.riasec.questions.index')
            ->with('success', 'Question mise à jour.');
    }

    // ══════════════════════════════════════════════════════════════════════
    // DELETE  DELETE /admin/riasec/questions/{id}
    // ══════════════════════════════════════════════════════════════════════

    public function destroy(QuestionRiasec $question)
    {
        $question->delete();
        return back()->with('success', 'Question supprimée.');
    }

    // ══════════════════════════════════════════════════════════════════════
    // TOGGLE ACTIF  POST /admin/riasec/questions/{id}/toggle
    // ══════════════════════════════════════════════════════════════════════

    public function toggle(QuestionRiasec $question)
    {
        $question->update(['actif' => ! $question->actif]);
        $state = $question->actif ? 'activée' : 'désactivée';
        return back()->with('success', "Question {$state}.");
    }

    // ══════════════════════════════════════════════════════════════════════
    // EXPORT CSV  GET /admin/riasec/export
    // ══════════════════════════════════════════════════════════════════════

    public function exportCsv(): StreamedResponse
    {
        $filename = 'riasec_resultats_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 pour Excel
            fwrite($handle, "\xEF\xBB\xBF");

            // En-têtes CSV
            fputcsv($handle, [
                'ID', 'Session', 'Code Holland',
                'Score R', 'Score I', 'Score A', 'Score S', 'Score E', 'Score C',
                'Cohérence', 'Nb Questions', 'Statut', 'Durée (min)', 'Complété le',
                'Utilisateur (anonymisé)',
            ], ';');

            // Données — chunking pour éviter les timeouts
            ProfileRiasec::with('user')
                ->where('statut', ProfileRiasec::STATUT_COMPLET)
                ->chunkById(200, function ($profils) use ($handle) {
                    foreach ($profils as $p) {
                        // Anonymisation : hash du user_id ou "invité"
                        $anonUser = $p->user_id
                            ? 'USR-' . substr(md5($p->user_id), 0, 8)
                            : 'INVITE-' . substr(md5($p->session_guest_id ?? $p->test_session_id), 0, 8);

                        fputcsv($handle, [
                            $p->id,
                            substr($p->test_session_id, 0, 8) . '…',
                            $p->code_holland,
                            $p->score_r, $p->score_i, $p->score_a,
                            $p->score_s, $p->score_e, $p->score_c,
                            $p->score_coherence ?? 'N/A',
                            $p->nb_questions_repondues,
                            $p->statut,
                            $p->duree_minutes ?? 'N/A',
                            $p->complete_at?->format('d/m/Y H:i') ?? '',
                            $anonUser,
                        ], ';');
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════
    // VALIDATION PARTAGÉE
    // ══════════════════════════════════════════════════════════════════════

    private function validateQuestion(Request $request): array
    {
        return $request->validate([
            'dimension'    => ['required', 'in:R,I,A,S,E,C'],
            'categorie'    => ['required', 'in:loisirs,preferences_professionnelles,qualites_personnelles'],
            'texte_fr'     => ['required', 'string', 'max:500'],
            'texte_ar'     => ['nullable', 'string', 'max:500'],
            'type_reponse' => ['required', 'in:likert,boolean,choice'],
            'poids'        => ['required', 'integer', 'min:1', 'max:3'],
            'ordre'        => ['required', 'integer', 'min:0', 'max:999'],
            'actif'        => ['boolean'],
            'source'       => ['nullable', 'string', 'max:100'],
            'version'      => ['nullable', 'string', 'max:10'],
        ], [
            'dimension.required'    => 'La dimension est obligatoire.',
            'dimension.in'          => 'La dimension doit être R, I, A, S, E ou C.',
            'texte_fr.required'     => 'Le texte de la question est obligatoire.',
            'texte_fr.max'          => 'Le texte ne doit pas dépasser 500 caractères.',
            'type_reponse.required' => 'Le type de réponse est obligatoire.',
            'poids.min'             => 'Le poids minimum est 1.',
            'poids.max'             => 'Le poids maximum est 3.',
        ]);
    }
}
