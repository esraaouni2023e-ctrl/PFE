<?php

namespace App\Console\Commands;

use App\Imports\FiliereImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportFilieresCommand extends Command
{
    /**
     * Signature de la commande.
     * Options :
     *   --file=nom.xlsx   → importer un seul fichier
     *   --dry-run         → simuler sans écrire en base
     */
    protected $signature = 'filieres:import
                            {--file= : Importer un seul fichier (nom uniquement, placé dans storage/app/excels/)}
                            {--dry-run : Afficher ce qui serait importé sans toucher la base}';

    protected $description = 'Importe les filières universitaires depuis les fichiers Excel dans storage/app/excels/';

    /**
     * Carte fichier → catégorie.
     * Pour ajouter une nouvelle source : ajouter une entrée ici, c'est tout.
     */
    private const FILE_MAP = [
        'SPORT_Filieres.xlsx'              => 'SPORT',
        'INFO_Filieres.xlsx'               => 'INFO',
        'TECH_Filieres.xlsx'               => 'TECH',
        'EXP_Filieres.xlsx'                => 'EXP',
        'ECO_Filieres.xlsx'                => 'ECO',
        'filieres_data.xlsx'               => 'MAT',
        'donnees_filiere_enrichies.xlsx'   => 'LET',
    ];

    // ══════════════════════════════════════════════════════════════════════
    // ENTRY POINT
    // ══════════════════════════════════════════════════════════════════════

    public function handle(): int
    {
        $this->info('');
        $this->info('  CapAvenir — Import des filières universitaires');
        $this->info('  ─────────────────────────────────────────────');

        $isDryRun  = $this->option('dry-run');
        $singleFile = $this->option('file');

        if ($isDryRun) {
            $this->warn('  Mode DRY-RUN activé — aucune donnée ne sera écrite en base.');
        }

        // Détermine la liste des fichiers à traiter
        $targets = $singleFile
            ? $this->resolveSingleFile($singleFile)
            : self::FILE_MAP;

        if (empty($targets)) {
            $this->error('  Aucun fichier valide à importer.');
            return self::FAILURE;
        }

        $totals = ['inserted' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => 0];

        foreach ($targets as $filename => $categorie) {
            $this->processFile($filename, $categorie, $isDryRun, $totals);
        }

        // ── Récapitulatif global ──────────────────────────────────────────
        $this->info('');
        $this->info('  ╔══════════════════════════════════╗');
        $this->info('  ║        Récapitulatif global       ║');
        $this->info('  ╠══════════════════════════════════╣');
        $this->info(sprintf('  ║  %-18s %10d  ║', 'Insérées :', $totals['inserted']));
        $this->info(sprintf('  ║  %-18s %10d  ║', 'Mises à jour :', $totals['updated']));
        $this->warn(sprintf('  ║  %-18s %10d  ║', 'Ignorées :', $totals['skipped']));

        if ($totals['errors'] > 0) {
            $this->error(sprintf('  ║  %-18s %10d  ║', 'Erreurs :', $totals['errors']));
        }

        $this->info('  ╚══════════════════════════════════╝');
        $this->info('');

        return $totals['errors'] > 0 ? self::FAILURE : self::SUCCESS;
    }

    // ══════════════════════════════════════════════════════════════════════
    // TRAITEMENT D'UN FICHIER
    // ══════════════════════════════════════════════════════════════════════

    private function processFile(
        string $filename,
        string $categorie,
        bool   $isDryRun,
        array  &$totals
    ): void {
        $path = "excels/{$filename}";

        // Vérification d'existence
        if (! Storage::disk('local')->exists($path)) {
            $this->warn("  [SKIP] Fichier introuvable : {$filename}");
            $totals['skipped']++;
            return;
        }

        $absolutePath = Storage::disk('local')->path($path);
        $this->line("  → <fg=cyan>{$filename}</> [<fg=yellow>{$categorie}</>]");

        if ($isDryRun) {
            $this->line("     (dry-run) serait importé avec la catégorie {$categorie}");
            return;
        }

        try {
            $import = new FiliereImport($categorie);
            Excel::import($import, $absolutePath);

            $this->line(
                "     <fg=green>✔</> Insérées: {$import->inserted} | "
                . "Mises à jour: {$import->updated} | "
                . "Ignorées: {$import->skipped}"
            );

            // Affiche les erreurs de lignes si présentes
            foreach ($import->failures() as $failure) {
                $this->warn(
                    "     Ligne {$failure->row()}: "
                    . implode(', ', $failure->errors())
                );
            }

            $totals['inserted'] += $import->inserted;
            $totals['updated']  += $import->updated;
            $totals['skipped']  += $import->skipped;

        } catch (\Exception $e) {
            $this->error("     <fg=red>✘</> Erreur sur {$filename}: {$e->getMessage()}");
            $totals['errors']++;
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    // RÉSOLUTION D'UN FICHIER UNIQUE (option --file)
    // ══════════════════════════════════════════════════════════════════════

    private function resolveSingleFile(string $filename): array
    {
        // Le fichier est-il dans la carte connue ?
        if (isset(self::FILE_MAP[$filename])) {
            return [$filename => self::FILE_MAP[$filename]];
        }

        // Sinon on demande la catégorie à l'utilisateur
        $categorie = $this->ask(
            "Catégorie pour '{$filename}' (INFO, TECH, ECO, EXP, SPORT, MAT, LET) ?"
        );

        if (! $categorie) {
            $this->error('Catégorie manquante. Import annulé.');
            return [];
        }

        return [$filename => strtoupper(trim($categorie))];
    }
}
