<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $studentName = auth()->check() ? auth()->user()->name : null;
        return view('student.dashboard', compact('studentName'));
    }
}
