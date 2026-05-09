<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdmissionPredictorService;

class StudentController extends Controller
{
    protected AdmissionPredictorService $predictor;

    public function __construct(AdmissionPredictorService $predictor)
    {
        $this->predictor = $predictor;
    }

    public function index()
    {
        $user = auth()->user();
        $studentName = $user ? $user->name : null;
        
        $profile = $user->profile;
        $portfolios = $user->portfolioItems()->latest()->get();
        $roadmaps = $user->careerRoadmaps()->latest()->get();
        
        // Formations de base (pour le MVP)
        $formations = [
            ['icon'=>'🖥️','name'=>'Licence Informatique', 'univ'=>'ESPRIT – Tunis'],
            ['icon'=>'📊','name'=>'Master Data Science', 'univ'=>'ENSI – La Manouba'],
            ['icon'=>'🤖','name'=>'Ingénierie IA', 'univ'=>'SUP\'COM – Tunis'],
            ['icon'=>'🔒','name'=>'Cybersécurité', 'univ'=>'ISI – Tunis'],
            ['icon'=>'🌐','name'=>'Développement Web', 'univ'=>'ISET – Sfax'],
            ['icon'=>'📱','name'=>'Développement Mobile', 'univ'=>'ISIM – Monastir'],
        ];
        
        // Calcul dynamique
        $predictions = $this->predictor->predictAdmissionChances($profile, $formations);

        return view('student.dashboard', compact(
            'studentName', 
            'profile', 
            'portfolios', 
            'roadmaps', 
            'predictions'
        ));
    }
}
