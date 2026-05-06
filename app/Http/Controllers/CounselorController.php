<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Profile;
use App\Models\Appointment;

class CounselorController extends Controller
{
    /**
     * Display a listing of students for the counselor.
     */
    public function index()
    {
        // Get all users with the 'student' role
        $students = User::where('role', User::ROLE_STUDENT)
            ->with(['profile', 'careerRoadmaps'])
            ->get();

        // 1. Analyse de Cohorte : Tendances d'orientation
        $cohortStats = [
            'Informatique & Tech' => 0,
            'Santé & Biologie' => 0,
            'Business & Management' => 0,
            'Art & Design' => 0,
            'Autres' => 0,
        ];
        
        $totalStudents = $students->count();
        if ($totalStudents > 0) {
            foreach ($students as $student) {
                // Determine their dominant interest based on profile skills/interests or roadmaps
                $interests = strtolower($student->profile->interests ?? '');
                if (str_contains($interests, 'tech') || str_contains($interests, 'info') || str_contains($interests, 'data')) {
                    $cohortStats['Informatique & Tech']++;
                } elseif (str_contains($interests, 'santé') || str_contains($interests, 'bio') || str_contains($interests, 'med')) {
                    $cohortStats['Santé & Biologie']++;
                } elseif (str_contains($interests, 'business') || str_contains($interests, 'commerce') || str_contains($interests, 'gestion')) {
                    $cohortStats['Business & Management']++;
                } elseif (str_contains($interests, 'art') || str_contains($interests, 'design')) {
                    $cohortStats['Art & Design']++;
                } else {
                    $cohortStats['Autres']++;
                }
            }
            // Convert to percentages
            foreach ($cohortStats as $key => $count) {
                $cohortStats[$key] = round(($count / $totalStudents) * 100);
            }
        }

        // 2. Gestion de Rendez-vous : Récupérer les RDV du conseiller
        $appointments = Appointment::where('counselor_id', auth()->id())
            ->with('student')
            ->orderBy('scheduled_at', 'asc')
            ->get();

        return view('counselor.dashboard', compact('students', 'cohortStats', 'appointments'));
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
        $student->load(['profile', 'profile.user', 'careerRoadmaps']);
        
        // Load test attempts
        $testAttempts = \App\Models\TestAttempt::where('user_id', $student->id)
            ->with('test')
            ->get();

        // Get past appointments for this student
        $appointments = Appointment::where('student_id', $student->id)
            ->where('counselor_id', auth()->id())
            ->orderBy('scheduled_at', 'desc')
            ->get();

        return view('counselor.student-profile', compact('student', 'testAttempts', 'appointments'));
    }

    /**
     * Update the student's profile with counselor feedback.
     */
    public function updateProfile(Request $request, User $student)
    {
        if (!$student->isStudent()) {
            abort(403);
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

    /**
     * Store a new appointment with a student.
     */
    public function storeAppointment(Request $request, User $student)
    {
        $request->validate([
            'scheduled_at' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        Appointment::create([
            'counselor_id' => auth()->id(),
            'student_id' => $student->id,
            'scheduled_at' => $request->scheduled_at,
            'status' => 'scheduled',
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Rendez-vous planifié avec succès.');
    }

    /**
     * Approve or Adjust manual matching.
     */
    public function approveMatch(Request $request, User $student)
    {
        $profile = $student->profile;
        if ($profile) {
            $profile->manual_match_approved = true;
            $profile->save();
        }

        return redirect()->back()->with('success', 'Matching du parcours validé manuellement.');
    }
}
