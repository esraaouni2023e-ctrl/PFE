<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CounselorController;
use App\Http\Controllers\OrientationController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\RoadmapController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Routes Web
|--------------------------------------------------------------------------
*/


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ══════════════════════════════════════════════
    // ESPACE ÉTUDIANT
    // ══════════════════════════════════════════════
    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {

        // ── Dashboard ──
        Route::get('/', [\App\Http\Controllers\StudentController::class, 'index'])
             ->name('dashboard');

        // ── Pipeline d'orientation (1 bouton → 3 étapes) ──
        Route::get('/pipeline', [\App\Http\Controllers\Student\OrientationPipelineController::class, 'start'])
             ->name('pipeline');
        Route::post('/pipeline/step1', [\App\Http\Controllers\Student\OrientationPipelineController::class, 'storeStep1'])
             ->name('pipeline.storeStep1');

        // ── Orientation (filières) ──
        Route::get('/orientation', [\App\Http\Controllers\OrientationController::class, 'index'])
             ->name('orientation');
        Route::get('/orientation/formation/{id}', [\App\Http\Controllers\OrientationController::class, 'show'])
             ->name('orientation.formation');
        Route::get('/orientation/nova', [\App\Http\Controllers\NovaOrientationController::class, 'index'])
             ->name('orientation.nova');
        Route::post('/orientation/nova/analyser', [\App\Http\Controllers\NovaOrientationController::class, 'analyze'])
             ->name('orientation.nova.analyze');
        Route::get('/orientation/nova/resultat', [\App\Http\Controllers\NovaOrientationController::class, 'result'])
             ->name('orientation.nova.result');

        // ── Simulateur What-If ──
        Route::prefix('whatif')->name('whatif.')->group(function () {
            Route::get('/',          [\App\Http\Controllers\Student\WhatIfController::class, 'index'])     ->name('index');
            Route::post('/calculer', [\App\Http\Controllers\Student\WhatIfController::class, 'calculer'])  ->name('calculer');
            Route::get('/matieres',  [\App\Http\Controllers\Student\WhatIfController::class, 'getMatieres'])->name('matieres');
            Route::get('/historique',[\App\Http\Controllers\Student\WhatIfController::class, 'historique']) ->name('historique');
            Route::delete('/historique/{simulation}', [\App\Http\Controllers\Student\WhatIfController::class, 'destroy'])->name('historique.destroy');
        });

        // ── Vœux d'orientation ──
        Route::prefix('voeux')->name('voeux.')->group(function () {
            Route::get('/',                         [\App\Http\Controllers\Student\VoeuxController::class, 'index'])    ->name('index');
            Route::post('/toggle/{formation}',      [\App\Http\Controllers\Student\VoeuxController::class, 'toggle'])   ->name('toggle');
            Route::post('/reordonner',              [\App\Http\Controllers\Student\VoeuxController::class, 'reordonner'])->name('reordonner');
            Route::patch('/{voeu}',                 [\App\Http\Controllers\Student\VoeuxController::class, 'update'])   ->name('update');
            Route::delete('/{voeu}',                [\App\Http\Controllers\Student\VoeuxController::class, 'destroy'])  ->name('destroy');
        });

        // ── Profil Académique ──
        Route::get('/profil',  [\App\Http\Controllers\Student\StudentProfileController::class, 'show'])  ->name('profil');
        Route::put('/profil',  [\App\Http\Controllers\Student\StudentProfileController::class, 'update'])->name('profil.update');

        // ── Comparateur de filières ──
        Route::prefix('comparateur')->name('comparateur.')->group(function () {
            Route::get('/',        [\App\Http\Controllers\Student\ComparateurController::class, 'index'])   ->name('index');
            Route::post('/data',   [\App\Http\Controllers\Student\ComparateurController::class, 'comparer'])->name('data');
        });

        // ── Portfolio & Roadmap (existants) ──
        Route::post('/portfolio',            [\App\Http\Controllers\PortfolioController::class, 'store'])   ->name('portfolio.store');
        Route::delete('/portfolio/{portfolio}',[\App\Http\Controllers\PortfolioController::class, 'destroy'])->name('portfolio.destroy');
        Route::post('/roadmap',              [\App\Http\Controllers\RoadmapController::class, 'generate'])  ->name('roadmap.generate');

        // ── Chatbot IA ──
        Route::post('/chatbot', [\App\Http\Controllers\ChatbotController::class, 'chat'])->name('chatbot');
    });

    Route::get('/counselor', [CounselorController::class, 'index'])
        ->name('counselor.dashboard')
        ->middleware('role:counselor');

    Route::get('/counselor/student/{student}', [CounselorController::class, 'showStudent'])
        ->name('counselor.student.show')
        ->middleware('role:counselor');

    Route::post('/counselor/student/{student}/update', [CounselorController::class, 'updateProfile'])
        ->name('counselor.student.update')
        ->middleware('role:counselor');

    Route::post('/counselor/student/{student}/match', [CounselorController::class, 'approveMatch'])
        ->name('counselor.student.match')
        ->middleware('role:counselor');

    Route::post('/counselor/student/{student}/appointments', [CounselorController::class, 'storeAppointment'])
        ->name('counselor.appointments.store')
        ->middleware('role:counselor');

});

// Admin Routes (Secured)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/delete/{user}', [UserController::class, 'destroy'])->name('users.delete');
    Route::post('/users/promote/{user}', [UserController::class, 'promote'])->name('users.promote');
    Route::post('/users/demote/{user}', [UserController::class, 'demote'])->name('users.demote');

    // References
    Route::get('/references', [\App\Http\Controllers\Admin\ReferenceController::class, 'index'])->name('references.index');
    Route::post('/references', [\App\Http\Controllers\Admin\ReferenceController::class, 'storeSection'])->name('references.store');
    Route::delete('/references/{section}', [\App\Http\Controllers\Admin\ReferenceController::class, 'destroySection'])->name('references.destroy');
    Route::post('/references/criteria', [\App\Http\Controllers\Admin\ReferenceController::class, 'storeCriterion'])->name('references.criteria.store');
    Route::delete('/references/criteria/{criterion}', [\App\Http\Controllers\Admin\ReferenceController::class, 'destroyCriterion'])->name('references.criteria.destroy');

    // Audit & Security
    Route::get('/audit', [\App\Http\Controllers\Admin\AuditController::class, 'index'])->name('audit.index');

    // ── RIASEC Admin ──────────────────────────────────────────────────────────
    Route::prefix('riasec')->name('riasec.')->group(function () {
        // Dashboard
        Route::get('/',        [\App\Http\Controllers\Admin\RiasecAdminController::class, 'dashboard'])->name('dashboard');
        // Export CSV
        Route::get('/export',  [\App\Http\Controllers\Admin\RiasecAdminController::class, 'exportCsv'])->name('export');

        // CRUD Questions
        Route::prefix('questions')->name('questions.')->group(function () {
            Route::get('/',             [\App\Http\Controllers\Admin\RiasecAdminController::class, 'index'])  ->name('index');
            Route::get('/create',       [\App\Http\Controllers\Admin\RiasecAdminController::class, 'create']) ->name('create');
            Route::post('/',            [\App\Http\Controllers\Admin\RiasecAdminController::class, 'store'])  ->name('store');
            Route::get('/{question}',   [\App\Http\Controllers\Admin\RiasecAdminController::class, 'edit'])   ->name('edit');
            Route::put('/{question}',   [\App\Http\Controllers\Admin\RiasecAdminController::class, 'update']) ->name('update');
            Route::delete('/{question}',[\App\Http\Controllers\Admin\RiasecAdminController::class, 'destroy'])->name('destroy');
            Route::post('/{question}/toggle',[\App\Http\Controllers\Admin\RiasecAdminController::class, 'toggle'])->name('toggle');
        });
    });

    // ── Filières : import Excel ──────────────────────────────────────────────
    Route::get('/filieres/import',              [\App\Http\Controllers\Admin\FiliereImportController::class, 'index']) ->name('filieres.import');
    Route::post('/filieres/import',             [\App\Http\Controllers\Admin\FiliereImportController::class, 'store']) ->name('filieres.import.store');
    Route::delete('/filieres/import/{categorie}',[\App\Http\Controllers\Admin\FiliereImportController::class, 'destroy'])->name('filieres.import.destroy');
});

// ── Test RIASEC ──────────────────────────────────────────────────────────
// Accessible aux utilisateurs authentifiés ET aux invités (pas de middleware auth).
// Le middleware riasec.test protège uniquement les étapes après démarrage.
Route::prefix('riasec')
    ->name('riasec.')
    ->middleware('web')
    ->group(function () {
        // ── Démarrage / phase initiale (accessible sans session de test) ─
        Route::redirect('/demarrer', '/riasec/question')->name('start');
        Route::get('/question',  [\App\Http\Controllers\RiasecTestController::class, 'start'])
             ->name('question.entry');
        Route::post('/question', [\App\Http\Controllers\RiasecTestController::class, 'initialize'])
             ->name('initialize');

        // ── Réinitialisation ────────────────────────────────────────────
        Route::delete('/reinitialiser', [\App\Http\Controllers\RiasecTestController::class, 'reset'])
             ->name('reset');

        // ── Simulation express (toutes étapes en 1 clic) ────────────────
        Route::post('/auto', [\App\Http\Controllers\RiasecTestController::class, 'autoRun'])
             ->name('auto');

        // ── Résultats (accessible sans session active, via profile_id) ──
        Route::get('/resultats', [\App\Http\Controllers\RiasecTestController::class, 'results'])
             ->name('results');

        // ── Étapes protégées : nécessitent une session de test active ───
        Route::middleware('riasec.test')->group(function () {
            Route::get('/question/{step}',[\App\Http\Controllers\RiasecTestController::class, 'showQuestion'])
                 ->name('question')
                 ->where('step', '[0-9]+');

            Route::post('/repondre',  [\App\Http\Controllers\RiasecTestController::class, 'storeAnswer'])
                 ->name('answer');

            Route::get('/terminer',   [\App\Http\Controllers\RiasecTestController::class, 'complete'])
                 ->name('complete');

            Route::get('/progression',[\App\Http\Controllers\RiasecTestController::class, 'progressJson'])
                 ->name('progress');
        });
    });

// ── Recommandations de filières (API Python) ──────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/recommendations',  [\App\Http\Controllers\RecommendationController::class, 'showForm'])
         ->name('recommendations.form');
    Route::post('/recommendations', [\App\Http\Controllers\RecommendationController::class, 'getRecommendations'])
         ->name('recommendations.get');
});

require __DIR__.'/auth.php';
