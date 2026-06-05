<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            if ($user->isCounselor()) {
                return redirect()->route('counselor.dashboard');
            }

            if ($user->isStudent()) {
                return redirect()->route('student.dashboard');
            }

            return redirect()->route('dashboard');
        }

        $testimonials = Testimonial::approved()->with('user')->latest()->get();

        return view('welcome', compact('testimonials'));
    }
}
