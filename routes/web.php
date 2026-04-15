<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CounselorController;
use App\Http\Controllers\OrientationController;
use App\Http\Controllers\ChatbotController;
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


    Route::get('/student', [StudentController::class, 'index'])
        ->name('student.dashboard')
        ->middleware('role:student');

    Route::get('/orientation', [OrientationController::class, 'index'])
        ->name('student.orientation')
        ->middleware('role:student');

    Route::get('/orientation/formation/{id}', [OrientationController::class, 'show'])
        ->name('student.orientation.formation')
        ->middleware('role:student');

    // ── Chatbot IA (Gemini) ──
    Route::post('/student/chatbot', [ChatbotController::class, 'chat'])
        ->name('student.chatbot')
        ->middleware('role:student');

    Route::get('/counselor', [CounselorController::class, 'index'])
        ->name('counselor.dashboard')
        ->middleware('role:counselor');

    Route::get('/counselor/student/{student}', [CounselorController::class, 'showStudent'])
        ->name('counselor.student.show')
        ->middleware('role:counselor');

    Route::post('/counselor/student/{student}/update', [CounselorController::class, 'updateProfile'])
        ->name('counselor.student.update')
        ->middleware('role:counselor');

 
});

// Admin Routes (Public - No Condition)
Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
Route::post('/admin/users/delete/{user}', [UserController::class, 'destroy'])->name('admin.users.delete');
Route::post('/admin/users/promote/{user}', [UserController::class, 'promote'])->name('admin.users.promote');
Route::post('/admin/users/demote/{user}', [UserController::class, 'demote'])->name('admin.users.demote');

require __DIR__.'/auth.php';
