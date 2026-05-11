<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCodeMail;

class TwoFactorController extends Controller
{
    public function index()
    {
        return view('auth.two-factor');
    }

    public function store(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|digits:6',
        ]);

        $user = auth()->user();

        if ($user->two_factor_expires_at && $user->two_factor_expires_at->lt(now())) {
            $user->resetTwoFactorCode();
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Votre code de vérification a expiré. Veuillez vous reconnecter.']);
        }

        if ($request->input('two_factor_code') !== $user->two_factor_code) {
            return back()->withErrors(['two_factor_code' => 'Le code de vérification est incorrect.']);
        }

        $user->resetTwoFactorCode();

        return redirect()->intended('/dashboard');
    }

    public function resend()
    {
        $user = auth()->user();
        
        $user->generateTwoFactorCode();
        
        Mail::to($user->email)->send(new TwoFactorCodeMail($user));
        
        return back()->with('success', 'Un nouveau code vous a été envoyé par email.');
    }
}
