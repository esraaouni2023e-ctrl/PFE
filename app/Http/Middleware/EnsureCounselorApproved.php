<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class EnsureCounselorApproved
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isCounselor()) {
            $isPendingRoute = $request->routeIs('counselor.pending');

            // Si le conseiller n'est pas approuvé
            if ($user->status !== User::STATUS_APPROVED) {
                // S'il n'est pas déjà sur la page d'attente ou en train de se déconnecter
                if (!$isPendingRoute && !$request->is('logout') && !$request->is('admin*')) {
                    return redirect()->route('counselor.pending');
                }
            } else {
                // S'il est approuvé mais essaye d'aller sur la page d'attente
                if ($isPendingRoute) {
                    return redirect()->route('counselor.dashboard');
                }
            }
        }

        return $next($request);
    }
}
