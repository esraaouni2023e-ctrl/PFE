<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Redirection prioritaire par rôle pour les utilisateurs standards
        if ($user->isCounselor()) {
            return redirect()->route('counselor.dashboard');
        }

        if ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        }

        // Les administrateurs (ou utilisateurs sans rôle spécifique) voient le portail de sélection
        // Cela permet aux admins d'accéder à toutes les interfaces
        return view('dashboard');
    }
}
