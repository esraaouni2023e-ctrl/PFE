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
            'ECO_Filieres.xlsx'              => 'Économie et Gestion',
            'EXP_Filieres.xlsx'              => 'Sciences Expérimentales',
            'filieres_data.xlsx'             => 'Mathématiques et Appliquées',
            'INFO_Filieres.xlsx'             => 'Informatique',
            'TECH_Filieres.xlsx'             => 'Technologie',
            'SPORT_Filieres.xlsx'            => 'Sport',
            'donnees_filiere_enrichies.xlsx' => 'Lettres et Sciences Humaines',
        ];

        $directory = storage_path('app/excels');
        
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

                        $gatbDefaults = $this->getGatbDefaults($domaine);

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
                                'g_requis'      => $gatbDefaults['G'],
                                'v_requis'      => $gatbDefaults['V'],
                                'n_requis'      => $gatbDefaults['N'],
                                's_requis'      => $gatbDefaults['S'],
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

    private function getGatbDefaults($domaine): array
    {
        return match($domaine) {
            'Mathématiques et Appliquées'   => ['G'=>12, 'V'=>10, 'N'=>13, 'S'=>11],
            'Informatique'                  => ['G'=>11, 'V'=>10, 'N'=>13, 'S'=>12],
            'Économie et Gestion'           => ['G'=>11, 'V'=>11, 'N'=>12, 'S'=>10],
            'Sciences Expérimentales'       => ['G'=>12, 'V'=>10, 'N'=>11, 'S'=>11],
            'Technologie'                   => ['G'=>11, 'V'=>10, 'N'=>12, 'S'=>13],
            'Lettres et Sciences Humaines'  => ['G'=>11, 'V'=>13, 'N'=>9,  'S'=>9],
            'Sport'                         => ['G'=>10, 'V'=>10, 'N'=>9,  'S'=>12],
            default                         => ['G'=>10, 'V'=>10, 'N'=>10, 'S'=>10],
        };
    }
}
