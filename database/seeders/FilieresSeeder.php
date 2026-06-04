<?php

namespace Database\Seeders;

use App\Models\Filiere;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class FilieresSeeder extends Seeder
{
    public function run(): void
    {
        $mapping = [
            'ECO_Filieres.csv'              => ['domaine' => 'Économie et Gestion', 'type_bac' => 'Économie et gestion'],
            'EXP_Filieres.csv'              => ['domaine' => 'Sciences Expérimentales', 'type_bac' => 'Sciences expérimentales'],
            'filieres_data.csv'             => ['domaine' => 'Mathématiques et Appliquées', 'type_bac' => 'Mathématiques'],
            'INFO_Filieres.csv'             => ['domaine' => 'Informatique', 'type_bac' => 'Informatique'],
            'TECH_Filieres.csv'             => ['domaine' => 'Technique', 'type_bac' => 'Technique'],
            'SPORT_Filieres.csv'            => ['domaine' => 'Sport', 'type_bac' => 'Sport'],
            'donnees_filiere_enrichies.csv' => ['domaine' => 'Lettres et Sciences Humaines', 'type_bac' => 'Lettres'],
        ];

        $directory = storage_path('app/excels');
        
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
            $this->command->warn("Directory $directory created. Please place your CSV files there.");
            return;
        }

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }
        Filiere::truncate();
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $records = [];

        foreach ($mapping as $filename => $info) {
            $path = $directory . '/' . $filename;
            $domaine = $info['domaine'];
            $typeBac = $info['type_bac'];

            if (File::exists($path)) {
                $this->command->info("Importing $filename ($domaine)...");
                
                if (($handle = fopen($path, 'r')) !== false) {
                    // Ignore the header row
                    fgetcsv($handle, 2048, ',');

                    while (($row = fgetcsv($handle, 2048, ',')) !== false) {
                        // Indices 0 à 6: Code_Filie, Nom_Filie, Université, Etablissement, SDO_2023, SDO_2024, SDO_2025
                        $code = isset($row[0]) ? trim($row[0]) : '';
                        if (empty($code)) {
                            continue;
                        }

                        $gatbDefaults = $this->getGatbDefaults($domaine);

                        $records[] = [
                            'code_filiere'       => $code,
                            'nom_filiere'        => $row[1] ?? '',
                            'universite'         => !empty($row[2]) ? $row[2] : null,
                            'etablissement'      => !empty($row[3]) ? $row[3] : null,
                            'sdo_2023'           => $this->cleanDecimal($row[4] ?? null),
                            'sdo_2024'           => $this->cleanDecimal($row[5] ?? null),
                            'sdo_2025'           => $this->cleanDecimal($row[6] ?? null),
                            'domaine'            => $domaine,
                            'code_riasec'        => !empty($row[7]) ? trim($row[7]) : null,
                            'taux_employabilite' => !empty($row[8]) ? trim($row[8]) : null,
                            'croissance_domaine' => !empty($row[9]) ? trim($row[9]) : null,
                            'type_bac'           => $typeBac,
                            'g_requis'           => $gatbDefaults['G'],
                            'v_requis'           => $gatbDefaults['V'],
                            'n_requis'           => $gatbDefaults['N'],
                            's_requis'           => $gatbDefaults['S'],
                            'created_at'         => now(),
                            'updated_at'         => now(),
                        ];
                    }
                    fclose($handle);
                }
            } else {
                $this->command->error("File $filename not found in $directory");
            }
        }

        DB::transaction(function () use ($records) {
            foreach (array_chunk($records, 500) as $chunk) {
                Filiere::insert($chunk);
            }
        });
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

