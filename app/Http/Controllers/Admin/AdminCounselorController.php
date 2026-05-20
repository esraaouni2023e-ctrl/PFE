<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CounselorProfile;

class AdminCounselorController extends Controller
{
    /**
     * Display a listing of counselor registration requests.
     */
    public function index(Request $request)
    {
        // Charger les conseillers avec leur profil professionnel
        $counselors = User::whereIn('role', [User::ROLE_COUNSELOR, User::ROLE_COUNSELOR_PENDING])
            ->with(['counselorProfile', 'counselorProfile.approver'])
            ->orderByRaw("FIELD(status, 'PENDING_APPROVAL', 'REJECTED', 'APPROVED')")
            ->orderBy('created_at', 'desc')
            ->get();

        // Répartir par état
        $pending = $counselors->where('status', User::STATUS_PENDING_APPROVAL);
        $approved = $counselors->where('status', User::STATUS_APPROVED);
        $rejected = $counselors->where('status', User::STATUS_REJECTED);

        return view('admin.counselors.index', [
            'counselors' => $counselors,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
        ]);
    }

    /**
     * Approve a counselor registration.
     */
    public function approve(User $user)
    {
        if (!$user->isCounselor()) {
            return redirect()->back()->with('error', 'Cet utilisateur n\'est pas un conseiller.');
        }

        $user->update([
            'role' => User::ROLE_COUNSELOR,
            'status' => User::STATUS_APPROVED,
        ]);

        $profile = $user->counselorProfile;
        if ($profile) {
            $profile->update([
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
        }

        return redirect()->route('admin.counselors.index')->with('success', "Le profil du conseiller {$user->name} a été approuvé avec succès.");
    }

    /**
     * Reject a counselor registration.
     */
    public function reject(Request $request, User $user)
    {
        if (!$user->isCounselor()) {
            return redirect()->back()->with('error', 'Cet utilisateur n\'est pas un conseiller.');
        }

        $request->validate([
            'verification_notes' => ['required', 'string', 'max:1000'],
        ]);

        $user->update([
            'status' => User::STATUS_REJECTED,
        ]);

        $profile = $user->counselorProfile;
        if ($profile) {
            $profile->update([
                'verification_notes' => $request->verification_notes,
            ]);
        }

        return redirect()->route('admin.counselors.index')->with('success', "La candidature de {$user->name} a été refusée avec le motif spécifié.");
    }
}
