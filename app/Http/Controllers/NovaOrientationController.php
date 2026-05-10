<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NovaOrientationService;

class NovaOrientationController extends Controller
{
    protected NovaOrientationService $novaService;

    public function __construct(NovaOrientationService $novaService)
    {
        $this->novaService = $novaService;
    }

    /**
     * Display the Nova orientation form.
     */
    public function index()
    {
        return view('student.nova.index');
    }

    /**
     * Analyze the student's profile using Nova AI.
     */
    public function analyze(Request $request)
    {
        $validated = $request->validate([
            'section_bac' => 'required|string',
            'mg' => 'required|numeric|min:0|max:20',
            // Main subjects mapping
            'notes' => 'required|array',
            'notes.*' => 'numeric|min:0|max:20',
            // Personality / Interests
            'personality_interests' => 'nullable|string|max:1000',
        ]);

        try {
            // Calculer le FG via l'IA Nova
            $studentData = [
                'section_bac' => $validated['section_bac'],
                'moyenne_generale' => $validated['mg'],
                'notes_matieres' => $validated['notes'],
            ];
            
            $result = $this->novaService->analyzeProfile($studentData);

            if (!$result || !isset($result['score_fg'])) {
                return back()->withInput()->with('error', 'Le calcul a échoué. Veuillez réessayer.');
            }

            // Store the result in the session to display on the result page
            session(['nova_result' => $result]);

            return redirect()->route('student.orientation.nova.result');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the Nova orientation result.
     */
    public function result()
    {
        $result = session('nova_result');

        if (!$result) {
            return redirect()->route('student.orientation.nova')->with('error', 'Aucun résultat trouvé. Veuillez remplir le formulaire.');
        }

        return view('student.nova.result', compact('result'));
    }
}
