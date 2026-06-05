<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AdminTestimonialController extends Controller
{
    /**
     * Display a listing of the testimonials with stats and filters.
     */
    public function index(Request $request)
    {
        $statusFilter = $request->input('status');
        $roleFilter = $request->input('role');

        // Stats
        $totalCount = Testimonial::count();
        $pendingCount = Testimonial::pending()->count();
        $approvedCount = Testimonial::approved()->count();
        $rejectedCount = Testimonial::rejected()->count();
        $archivedCount = Testimonial::archived()->count();
        $averageRating = Testimonial::approved()->avg('rating') ?? 0;

        // Query testimonials
        $query = Testimonial::with('user');

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        if ($roleFilter) {
            $query->whereHas('user', function ($q) use ($roleFilter) {
                $q->where('role', $roleFilter);
            });
        }

        $testimonials = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.testimonials.index', compact(
            'testimonials',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'archivedCount',
            'averageRating'
        ));
    }

    /**
     * Approve a testimonial.
     */
    public function approve(Request $request, Testimonial $testimonial)
    {
        $testimonial->update(['status' => Testimonial::STATUS_APPROVED]);

        $this->logAction($request, 'testimonial_approve', $testimonial);

        return redirect()->back()->with('success', 'Le témoignage a été approuvé avec succès et est maintenant visible sur la page d\'accueil.');
    }

    /**
     * Reject a testimonial.
     */
    public function reject(Request $request, Testimonial $testimonial)
    {
        $testimonial->update(['status' => Testimonial::STATUS_REJECTED]);

        $this->logAction($request, 'testimonial_reject', $testimonial);

        return redirect()->back()->with('success', 'Le témoignage a été rejeté.');
    }

    /**
     * Archive a testimonial.
     */
    public function archive(Request $request, Testimonial $testimonial)
    {
        $testimonial->update(['status' => Testimonial::STATUS_ARCHIVED]);

        $this->logAction($request, 'testimonial_archive', $testimonial);

        return redirect()->back()->with('success', 'Le témoignage a été archivé.');
    }

    /**
     * Delete a testimonial.
     */
    public function destroy(Request $request, Testimonial $testimonial)
    {
        $testimonial->delete();

        $this->logAction($request, 'testimonial_delete', $testimonial);

        return redirect()->back()->with('success', 'Le témoignage a été supprimé définitivement.');
    }

    /**
     * Log the action to audit log.
     */
    protected function logAction(Request $request, string $action, Testimonial $testimonial)
    {
        try {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'details' => json_encode([
                    'testimonial_id' => $testimonial->id,
                    'testimonial_user_id' => $testimonial->user_id,
                    'testimonial_user_name' => $testimonial->user?->name,
                ]),
                'ip_address' => $request->ip(),
            ]);
        } catch (\Exception $e) {
            // Silence logging errors
        }
    }
}
