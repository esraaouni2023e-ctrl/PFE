<?php

namespace App\Services\Cv;

use App\Models\CvProfile;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;

class DocxGeneratorService
{
    /**
     * Génère et télécharge le CV au format DOCX.
     */
    public function generate(CvProfile $cvProfile)
    {
        $phpWord = new PhpWord();
        $user = $cvProfile->user;

        // ─── Styles globaux ───
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(11);

        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 22, 'color' => '2C3E50'], ['spaceAfter' => 120]);
        $phpWord->addTitleStyle(2, ['bold' => true, 'size' => 13, 'color' => 'D4622A', 'allCaps' => true], [
            'spaceAfter' => 60,
            'spaceBefore' => 200,
            'borderBottom' => ['size' => 6, 'color' => 'D4622A'],
        ]);

        $section = $phpWord->addSection([
            'marginTop'    => 600,
            'marginBottom' => 600,
            'marginLeft'   => 800,
            'marginRight'  => 800,
        ]);

        // ─── En-tête : Nom + Poste visé ───
        $section->addTitle($user->name, 1);

        if ($cvProfile->target_job) {
            $section->addText($cvProfile->target_job, ['size' => 12, 'color' => '7F8C8D', 'italic' => true]);
        }

        $section->addText($user->email, ['size' => 10, 'color' => '95A5A6']);
        $section->addTextBreak();

        // ─── Résumé professionnel ───
        if ($cvProfile->summary) {
            $section->addTitle('Profil', 2);
            $section->addText(
                strip_tags($cvProfile->summary),
                ['size' => 10.5, 'color' => '333333'],
                ['spaceAfter' => 120, 'lineSpacing' => 1.15]
            );
        }

        // ─── Expériences ───
        if ($cvProfile->experiences->isNotEmpty()) {
            $section->addTitle('Expériences Professionnelles', 2);

            foreach ($cvProfile->experiences as $exp) {
                $section->addText(
                    $exp->position . '  —  ' . $exp->company,
                    ['bold' => true, 'size' => 11]
                );

                $dates = $exp->start_date->format('m/Y') . ' — '
                    . ($exp->is_current ? 'Présent' : $exp->end_date?->format('m/Y'));
                $section->addText($dates, ['size' => 9, 'color' => '95A5A6', 'italic' => true]);

                $section->addText(
                    strip_tags($exp->description),
                    ['size' => 10],
                    ['spaceAfter' => 100, 'lineSpacing' => 1.15]
                );
            }
        }

        // ─── Formations ───
        if ($cvProfile->educations->isNotEmpty()) {
            $section->addTitle('Formation', 2);

            foreach ($cvProfile->educations as $edu) {
                $section->addText(
                    $edu->degree . ($edu->field_of_study ? ' — ' . $edu->field_of_study : ''),
                    ['bold' => true, 'size' => 11]
                );
                $section->addText($edu->institution, ['size' => 10, 'color' => '555555']);

                $dates = $edu->start_date->format('Y') . ' — '
                    . ($edu->is_current ? 'Présent' : $edu->end_date?->format('Y'));
                $section->addText($dates, ['size' => 9, 'color' => '95A5A6', 'italic' => true]);

                if ($edu->description) {
                    $section->addText(strip_tags($edu->description), ['size' => 10], ['spaceAfter' => 80]);
                }
            }
        }

        // ─── Compétences ───
        if ($cvProfile->skills->isNotEmpty()) {
            $section->addTitle('Compétences', 2);

            $skillTexts = $cvProfile->skills->map(function ($s) {
                return $s->name . ($s->level ? ' (' . $s->level . ')' : '');
            })->implode('  •  ');

            $section->addText($skillTexts, ['size' => 10], ['spaceAfter' => 60]);
        }

        // ─── Langues ───
        if ($cvProfile->languages->isNotEmpty()) {
            $section->addTitle('Langues', 2);

            foreach ($cvProfile->languages as $lang) {
                $section->addText(
                    $lang->name . ($lang->level ? ' — ' . $lang->level : ''),
                    ['size' => 10],
                    ['spaceAfter' => 40]
                );
            }
        }

        // ─── Génération du fichier ───
        $filename = 'CV_' . Str::slug($user->name) . '_' . now()->format('Y-m-d') . '.docx';
        $tempPath = storage_path('app/temp/' . $filename);

        // Créer le dossier temp s'il n'existe pas
        if (!is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
    }
}
