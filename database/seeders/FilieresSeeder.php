<?php

namespace Database\Seeders;

use App\Models\Filiere;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class FilieresSeeder extends Seeder
{
    public function run(): void
    {
        $mapping = [
            'ECO_Filieres.xlsx'           => 'Économie et Gestion',
            'EXP_Filieres-1.xlsx'         => 'Sciences Expérimentales',
            'filieres_data (1).xlsx'      => 'Mathématiques et Appliquées',
            'INFO_Filieres.xlsx'          => 'Informatique',
            'TECH_Filieres.xlsx'          => 'Technologie',
            'SPORT_Filieres.xlsx'         => 'Sport',
            'donnees_filiere_enrichies-1.xlsx' => 'Lettres et Sciences Humaines',
        ];

        $directory = storage_path('app/excel');
        
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
            $this->command->warn("Directory $directory created. Please place your Excel files there.");
            return;
        }

        foreach ($mapping as $filename => $domaine) {
            $path = $directory . '/' . $filename;

            if (File::exists($path)) {
                $this->command->info("Importing $filename ($domaine)...");
                $data = Excel::toArray([], $path);
                
                if (!empty($data) && isset($data[0])) {
                    $rows = $data[0];
                    unset($rows[0]); // Ignorer l'en-tête

                    foreach ($rows as $row) {
                        // Indices 0 à 6: Code_Filie, Nom_Filie, Université, Etablissement, SDO_2023, SDO_2024, SDO_2025
                        $code = trim($row[0] ?? '');
                        if (empty($code)) continue;

                        Filiere::updateOrCreate(
                            ['code_filiere' => $code],
                            [
                                'nom_filiere'   => $row[1] ?? '',
                                'universite'    => $row[2] ?? null,
                                'etablissement' => $row[3] ?? null,
                                'sdo_2023'      => $this->cleanDecimal($row[4]),
                                'sdo_2024'      => $this->cleanDecimal($row[5]),
                                'sdo_2025'      => $this->cleanDecimal($row[6]),
                                'domaine'       => $domaine,
                            ]
                        );
                    }
                }
            } else {
                $this->command->error("File $filename not found in $directory");
            }
        }
    }

    private function cleanDecimal($value)
    {
        if (is_null($value) || $value === '' || str_contains($value, 'Non fourni')) {
            return null;
        }
        
        // Remplacer virgule par point
        $value = str_replace(',', '.', $value);
        
        // Garder uniquement chiffres et point
        $value = preg_replace('/[^0-9.]/', '', $value);
        
        return is_numeric($value) ? (float)$value : null;
    }
}
