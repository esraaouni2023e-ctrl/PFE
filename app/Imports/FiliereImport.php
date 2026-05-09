<?php

namespace App\Imports;

use App\Models\Filiere;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Collection;
use Throwable;

class FiliereImport implements
    ToModel,
    WithHeadingRow,
    SkipsEmptyRows,
    WithChunkReading,
    SkipsOnError
{
    use SkipsErrors;

    /**
     * Catégorie à associer à toutes les lignes de ce fichier.
     * Ex : "INFO", "TECH", "ECO"…
     */
    private string $categorie;

    /** Compteurs pour le rapport d'import. */
    public int $inserted = 0;
    public int $updated  = 0;
    public int $skipped  = 0;

    public function __construct(string $categorie)
    {
        $this->categorie = strtoupper(trim($categorie));
    }

    // ══════════════════════════════════════════════════════════════════════
    // MAPPING LIGNE → MODÈLE
    // ══════════════════════════════════════════════════════════════════════

    public function model(array $row): ?Filiere
    {
        // Ignore les lignes sans code_filiere
        $code = trim((string) ($row['code_filiere'] ?? ''));
        if ($code === '') {
            $this->skipped++;
            return null;
        }

        $data = [
            'categorie'          => $this->categorie,
            'nom_filiere'        => trim((string) ($row['nom_filiere'] ?? '')),
            'universite'         => $this->strOrNull($row['universite'] ?? null),
            'etablissement'      => $this->strOrNull($row['etablissement'] ?? null),
            'sdo_2023'           => $this->toFloat($row['sdo_2023'] ?? null),
            'sdo_2024'           => $this->toFloat($row['sdo_2024'] ?? null),
            'sdo_2025'           => $this->toFloat($row['sdo_2025'] ?? null),
            'code_riasec'        => $this->strOrNull($row['code_riasec'] ?? null),
            'taux_employabilite' => $this->toFloat($row['taux_employabilite'] ?? null),
            'croissance_domaine' => $this->toFloat($row['croissance_domaine'] ?? null),
            'alignment_national' => $this->toFloat($row['alignment_national'] ?? null),
            'source'             => $this->strOrNull($row['source'] ?? null),
        ];

        // updateOrCreate évite les doublons sur code_filiere
        $exists = Filiere::where('code_filiere', $code)->exists();

        $filiere = Filiere::updateOrCreate(
            ['code_filiere' => $code],
            $data
        );

        $exists ? $this->updated++ : $this->inserted++;

        // updateOrCreate retourne le model mais ToModel attend qu'on retourne
        // null pour ne pas faire un second insert — on retourne null ici.
        return null;
    }

    // ══════════════════════════════════════════════════════════════════════
    // LECTURE PAR CHUNKS (évite les timeout mémoire sur gros fichiers)
    // ══════════════════════════════════════════════════════════════════════

    public function chunkSize(): int
    {
        return 200;
    }

    // ══════════════════════════════════════════════════════════════════════
    // HELPERS DE CONVERSION
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Convertit une valeur en float nullable.
     * Gère : vide, null, "(Non fourni)", chaînes avec virgule, pourcentages.
     */
    private function toFloat(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        $str = trim((string) $value);

        // Valeurs textuelles signifiant "absent"
        if (in_array(strtolower($str), ['(non fourni)', 'n/a', 'nd', '-', ''], true)) {
            return null;
        }

        // Supprime les espaces, remplace la virgule décimale par un point
        $str = str_replace([' ', ','], ['', '.'], $str);

        // Supprime le symbole % si présent (on stocke en décimal)
        if (str_ends_with($str, '%')) {
            $str = rtrim($str, '%');
            $float = filter_var($str, FILTER_VALIDATE_FLOAT);
            return $float !== false ? round($float / 100, 4) : null;
        }

        $float = filter_var($str, FILTER_VALIDATE_FLOAT);
        return $float !== false ? (float) $float : null;
    }

    /**
     * Retourne null si la chaîne est vide ou "(Non fourni)".
     */
    private function strOrNull(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $str = trim((string) $value);

        if ($str === '' || strtolower($str) === '(non fourni)') {
            return null;
        }

        return $str;
    }
}
