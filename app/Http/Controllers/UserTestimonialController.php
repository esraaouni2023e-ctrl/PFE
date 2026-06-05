<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTestimonialController extends Controller
{
    /**
     * Show the form for editing the testimonial.
     */
    public function edit()
    {
        $user = Auth::user();
        $testimonial = $user->testimonial ?? new Testimonial();

        return view('testimonial.edit', compact('testimonial', 'user'));
    }

    /**
     * Update the testimonial.
     */
    public function update(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ], [
            'rating.required' => 'La note est obligatoire.',
            'rating.integer' => 'La note doit être un entier.',
            'rating.min' => 'La note minimale est de 1 étoile.',
            'rating.max' => 'La note maximale est de 5 étoiles.',
            'comment.required' => 'Le commentaire est obligatoire.',
            'comment.min' => 'Le commentaire doit faire au moins 10 caractères.',
            'comment.max' => 'Le commentaire ne peut pas dépasser 1000 caractères.',
        ]);

        $user = Auth::user();

        // Enforce one testimonial per user, setting status back to pending upon update
        $testimonial = Testimonial::updateOrCreate(
            ['user_id' => $user->id],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
                'status' => Testimonial::STATUS_PENDING,
            ]
        );

        // Audit Log if AuditLog exists
        try {
            if (class_exists(\App\Models\AuditLog::class)) {
                \App\Models\AuditLog::create([
                    'user_id' => $user->id,
                    'action' => 'testimonial_update',
                    'details' => json_encode(['testimonial_id' => $testimonial->id]),
                    'ip_address' => $request->ip(),
                ]);
            }
        } catch (\Exception $e) {
            // Ignore audit logging errors in non-critical environments
        }

        return redirect()->back()->with('success', 'Votre témoignage a été soumis avec succès et est en attente de validation par l\'administration.');
    }
}
