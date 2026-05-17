<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            $url = $request->session()->pull('url.intended', RouteServiceProvider::HOME);
            if (!str_contains($url, 'verified=1')) {
                $url .= (str_contains($url, '?') ? '&' : '?') . 'verified=1';
            }
            return redirect()->to($url);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $url = $request->session()->pull('url.intended', RouteServiceProvider::HOME);
        if (!str_contains($url, 'verified=1')) {
            $url .= (str_contains($url, '?') ? '&' : '?') . 'verified=1';
        }
        return redirect()->to($url);
    }
}
