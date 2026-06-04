<?php

namespace App\Http\Controllers;

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
        return view('welcome');
    }
}
