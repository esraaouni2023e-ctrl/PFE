<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Filiere;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportFilieres extends Command
{
    protected $signature = 'app:import-filieres';
    protected $description = 'Importe les filières depuis le fichier Excel enrichi';

    public function handle()
    {
        $this->info("Importation des filières depuis storage/app/excels/filieres_data.xlsx...");

        $path = storage_path('app/excels/filieres_data.xlsx');
        if (!file_exists($path)) {
            // Fallback
            $path = storage_path('app/excels/donnees_filiere_enrichies.xlsx');
            if (!file_exists($path)) {
                $this->error("Fichier Excel introuvable.");
                return 1;
            }
        }

        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        $headers = array_map('trim', array_filter($data[0]));
        $inserted = 0;

        // Truncate existing data to start fresh
        Filiere::truncate();

        foreach (array_slice($data, 1) as $row) {
            if (empty($row[0])) continue; // Skip empty rows

            $filiereData = [];
            foreach ($headers as $index => $header) {
                $filiereData[$header] = $row[$index] ?? null;
            }

            // Mappage des valeurs texte en pourcentages (0.0 à 1.0)
            $mapQualitative = function($val) {
                if (!$val) return 0.5;
                $val = strtolower(trim($val));
                if (in_array($val, ['très élevé', 'tres eleve', 'très forte', 'excellent'])) return 0.95;
                if (in_array($val, ['élevé', 'eleve', 'forte', 'bon'])) return 0.85;
                if (in_array($val, ['moyen', 'modéré', 'stable'])) return 0.65;
                if (in_array($val, ['faible', 'bas', 'basse'])) return 0.40;
                if (in_array($val, ['très faible', 'tres faible'])) return 0.20;
                return 0.5;
            };

            Filiere::create([
                'code_filiere'       => $filiereData['Code_Filiere'] ?? uniqid('FIL'),
                'categorie'          => 'IND', // Par défaut
                'nom_filiere'        => $filiereData['Nom_Filiere'] ?? 'Inconnu',
                'universite'         => $filiereData['Universite'] ?? null,
                'etablissement'      => $filiereData['Etablissement'] ?? null,
                'sdo_2023'           => round((float) str_replace(',', '.', $filiereData['SDO_2023'] ?? 0), 2),
                'sdo_2024'           => round((float) str_replace(',', '.', $filiereData['SDO_2024'] ?? 0), 2),
                'sdo_2025'           => round((float) str_replace(',', '.', $filiereData['SDO_2025'] ?? 0), 2),
                'code_riasec'        => $filiereData['Code_RIASEC'] ?? null,
                'taux_employabilite' => $mapQualitative($filiereData['Taux_Employabilite'] ?? null),
                'croissance_domaine' => $mapQualitative($filiereData['Croissance_Domaine'] ?? null),
                'alignment_national' => $mapQualitative($filiereData['Alignment_National'] ?? null),
                'source'             => $filiereData['source'] ?? null,
            ]);

            $inserted++;
        }

        $this->info("Importation terminée ! $inserted filières ajoutées.");
        return 0;
    }
}
