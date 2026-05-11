<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureTestInProgress — Vérifie qu'une session de test RIASEC est bien active.
 *
 * Si aucune session n'est trouvée en session PHP, redirige vers le démarrage du test.
 * Protège les routes showQuestion, storeAnswer, results, complete.
 */
class EnsureTestInProgress
{
    public function handle(Request $request, Closure $next): Response
    {
        $sessionId = session('riasec_session_id');

        if (! $sessionId) {
            $message = 'Aucun test en cours. Veuillez d\'abord démarrer le test.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'redirect' => route('riasec.question.entry'),
                ], 422);
            }

            return redirect()
                ->route('riasec.question.entry')
                ->with('warning', $message);
        }

        // Injecte le sessionId dans la requête pour usage dans le contrôleur
        $request->merge(['_riasec_session_id' => $sessionId]);

        return $next($request);
    }
}
