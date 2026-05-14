<?php

namespace App\Services\Cv;

use App\Models\CvProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class PdfGeneratorService
{
    /**
     * Génère et télécharge le CV au format PDF.
     */
    public function generate(CvProfile $cvProfile)
    {
        $template = 'cv_templates.' . $cvProfile->template_name . '_pdf';

        // Fallback si le template n'existe pas
        if (!view()->exists($template)) {
            $template = 'cv_templates.modern_pdf';
        }

        $pdf = Pdf::loadView($template, [
            'cv'   => $cvProfile,
            'user' => $cvProfile->user,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'CV_' . Str::slug($cvProfile->user->name) . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}
