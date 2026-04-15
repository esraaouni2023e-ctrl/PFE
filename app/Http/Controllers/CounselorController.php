<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Profile;

class CounselorController extends Controller
{
    /**
     * Display a listing of students for the counselor.
     */
    public function index()
    {
        // Get all users with the 'student' role
        $students = User::where('role', User::ROLE_STUDENT)
            ->with(['profile'])
            ->get();

        return view('counselor.dashboard', compact('students'));
    }

    /**
     * Display the specified student profile.
     */
    public function showStudent(User $student)
    {
        // Ensure the user is a student
        if (!$student->isStudent()) {
            abort(403);
        }

        // Load profile and other related data (tests, recommendations)
        $student->load(['profile', 'profile.user']);
        
        // Load test attempts
        $testAttempts = \App\Models\TestAttempt::where('user_id', $student->id)
            ->with('test')
            ->get();

        return view('counselor.student-profile', compact('student', 'testAttempts'));
    }

    /**
     * Update the student's profile with counselor feedback.
     */
    public function updateProfile(Request $request, User $student)
    {
        if (!$student->isStudent()) {
            abort(430);
        }

        $request->validate([
            'counselor_observations' => 'nullable|string',
            'coaching_plan' => 'nullable|string',
            'status' => 'required|in:pending,ongoing,completed',
        ]);

        $profile = $student->profile ?: new Profile(['user_id' => $student->id]);
        
        $profile->fill([
            'counselor_observations' => $request->counselor_observations,
            'coaching_plan' => $request->coaching_plan,
            'status' => $request->status,
        ]);
        
        $profile->save();

        return redirect()->route('counselor.student.show', $student)
            ->with('success', 'Profil étudiant mis à jour avec succès.');
    }
}
