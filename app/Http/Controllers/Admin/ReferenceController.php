<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferenceSection;
use App\Models\ReferenceCriterion;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    public function index()
    {
        $sections = ReferenceSection::with('criteria')->get();
        return view('admin.references', compact('sections'));
    }

    public function storeSection(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'required_bac_score' => 'required|numeric|min:0|max:20',
        ]);

        ReferenceSection::create($request->all());

        return redirect()->back()->with('success', 'Filière ajoutée avec succès.');
    }

    public function storeCriterion(Request $request)
    {
        $request->validate([
            'reference_section_id' => 'required|exists:reference_sections,id',
            'subject' => 'required|string|max:255',
            'coefficient' => 'required|numeric|min:0.1',
        ]);

        ReferenceCriterion::create($request->all());

        return redirect()->back()->with('success', 'Critère ajouté avec succès.');
    }

    public function destroySection(ReferenceSection $section)
    {
        $section->delete();
        return redirect()->back()->with('success', 'Filière supprimée.');
    }

    public function destroyCriterion(ReferenceCriterion $criterion)
    {
        $criterion->delete();
        return redirect()->back()->with('success', 'Critère supprimé.');
    }
}
