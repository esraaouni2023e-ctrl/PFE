<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PortfolioItem;
use App\Services\OllamaService;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class PortfolioController extends Controller
{
    protected OllamaService $ollama;

    public function __construct(OllamaService $ollama)
    {
        $this->ollama = $ollama;
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:document,certificate,project',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = auth()->user();
        $file = $request->file('file');
        $path = $file->store('portfolios', 'public');

        $portfolioItem = PortfolioItem::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => $path,
        ]);

        // Optionnel : Extraire le texte du PDF si c'en est un
        $textContent = "";
        if ($file->getClientOriginalExtension() === 'pdf') {
            try {
                $parser = new Parser();
                $pdf = $parser->parseFile(storage_path('app/public/' . $path));
                $textContent = $pdf->getText();
            } catch (\Exception $e) {
                // Ignore parse errors
                $textContent = $request->title . " - Document uploadé.";
            }
        } else {
            $textContent = "Fichier image / non texte : " . $request->title;
        }

        // Lancer l'analyse via Ollama en arrière-plan ou directement (MVP direct)
        $analysis = $this->ollama->analyzePortfolioItem($textContent);

        if ($analysis && is_array($analysis)) {
            $portfolioItem->update([
                'ai_analysis_summary' => $analysis['summary'] ?? null,
                'extracted_skills' => $analysis['skills'] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Document ajouté avec succès au portfolio !');
    }

    public function destroy(PortfolioItem $portfolio)
    {
        if ($portfolio->user_id !== auth()->id()) {
            abort(403);
        }

        Storage::disk('public')->delete($portfolio->file_path);
        $portfolio->delete();

        return redirect()->back()->with('success', 'Document supprimé.');
    }
}
