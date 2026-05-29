<?php

namespace App\Services;

use App\Models\Formation;
use App\Models\User;

/**
 * FutureSimulatorService — Moteur du simulateur de futurs académiques.
 *
 * Fournit les calculs pour les modules :
 * 1. Variation de notes (Score FG)
 * 2. Changement de spécialité
 * 3. Filière alternative
 * 4. Secteurs & Employabilité
 * 5. Salaires & ROI
 * 6. Compatibilité Carrière
 */
class FutureSimulatorService
{
    public function __construct(private readonly ScoreFGService $scoreFg) {}

    // ══════════════════════════════════════════════════════════════
    //  CONSTANTES — Données de référence tunisiennes
    // ══════════════════════════════════════════════════════════════



    /** Secteurs tunisiens — taux insertion, saturation, croissance */
    private const SECTEURS = [
        'informatique'   => ['label' => 'Informatique & TIC',   'insertion' => 88, 'saturation' => 35, 'croissance' => 12, 'icon' => '💻', 'salaire_moy' => 1800],
        'sante'          => ['label' => 'Santé & Paramédical',  'insertion' => 92, 'saturation' => 25, 'croissance' => 8,  'icon' => '🏥', 'salaire_moy' => 2200],
        'ingenierie'     => ['label' => 'Ingénierie',           'insertion' => 78, 'saturation' => 40, 'croissance' => 6,  'icon' => '⚙️', 'salaire_moy' => 2000],
        'commerce'       => ['label' => 'Commerce & Marketing', 'insertion' => 65, 'saturation' => 60, 'croissance' => 4,  'icon' => '📊', 'salaire_moy' => 1400],
        'finance'        => ['label' => 'Finance & Comptabilité','insertion' => 72, 'saturation' => 50, 'croissance' => 3, 'icon' => '💰', 'salaire_moy' => 1600],
        'droit'          => ['label' => 'Droit & Juridique',    'insertion' => 55, 'saturation' => 65, 'croissance' => 2,  'icon' => '⚖️', 'salaire_moy' => 1300],
        'education'      => ['label' => 'Éducation & Formation','insertion' => 60, 'saturation' => 55, 'croissance' => 3,  'icon' => '📚', 'salaire_moy' => 1100],
        'tourisme'       => ['label' => 'Tourisme & Hôtellerie','insertion' => 58, 'saturation' => 45, 'croissance' => 7,  'icon' => '✈️', 'salaire_moy' => 1000],
        'agriculture'    => ['label' => 'Agriculture & Agro',   'insertion' => 50, 'saturation' => 30, 'croissance' => 5,  'icon' => '🌾', 'salaire_moy' => 900],
        'arts'           => ['label' => 'Arts & Design',        'insertion' => 45, 'saturation' => 40, 'croissance' => 6,  'icon' => '🎨', 'salaire_moy' => 1100],
    ];

    /** Grille salariale (TND/mois) par niveau et domaine */
    private const SALAIRES = [
        'licence'   => ['min' => 800,  'max' => 1400, 'apres5' => 1800, 'apres10' => 2400],
        'ingenieur' => ['min' => 1400, 'max' => 2200, 'apres5' => 3000, 'apres10' => 4500],
        'master'    => ['min' => 1200, 'max' => 2000, 'apres5' => 2800, 'apres10' => 4000],
        'doctorat'  => ['min' => 1800, 'max' => 2800, 'apres5' => 3800, 'apres10' => 5500],
    ];

    /** Correspondance RIASEC → domaines professionnels */
    private const RIASEC_DOMAINES = [
        'R' => ['ingenierie', 'agriculture', 'informatique'],
        'I' => ['sante', 'ingenierie', 'informatique'],
        'A' => ['arts', 'education', 'tourisme'],
        'S' => ['education', 'sante', 'droit'],
        'E' => ['commerce', 'finance', 'tourisme'],
        'C' => ['finance', 'commerce', 'droit'],
    ];

    // ══════════════════════════════════════════════════════════════
    //  MODULE 1 — Variation de notes
    // ══════════════════════════════════════════════════════════════

    public function simulerVariationNotes(string $section, float $mg, array $notes): array
    {
        $scoreFg = $this->scoreFg->calculer($section, $mg, $notes);
        $formations = $this->scoreFg->getFormationsAccessibles($scoreFg, 8);
        $niveau = $this->getNiveauScore($scoreFg);

        return [
            'score_fg'   => $scoreFg,
            'niveau'     => $niveau,
            'formations' => $formations->map(fn($f) => [
                'id'            => $f->id,
                'nom'           => $f->nom,
                'icon'          => $f->icon,
                'etablissement' => $f->etablissement,
                'niveau'        => $f->niveau,
                'duree'         => $f->duree,
                'score_matching'=> $f->score_matching,
            ])->toArray(),
            'chances_admission' => min(95, round(($scoreFg / 200) * 100)),
        ];
    }

    // ══════════════════════════════════════════════════════════════
    //  MODULE 2 — Changement de spécialité
    // ══════════════════════════════════════════════════════════════

    public function simulerChangementSpecialite(string $sectionActuelle, float $scoreActuel, array $notes, string $nouvelleSection): array
    {
        // 1. Calculer la moyenne générale équivalente à partir du score actuel saisi par l'utilisateur
        $coefSumActuel = $this->scoreFg->getCoefficientsSum($sectionActuelle);
        $mg = min(20.0, max(0.0, $scoreActuel / $coefSumActuel));

        // 2. Calculer le score pour la nouvelle section avec cette moyenne générale et les notes
        $scoreNouveau = $this->scoreFg->calculer($nouvelleSection, $mg, $notes);
        $delta = round($scoreNouveau - $scoreActuel, 2);

        // 3. Compter le nombre de filières accessibles dans le domaine propre à chaque section (SDO <= score)
        $mapBacDomaine = [
            'Mathématiques'           => 'Mathématiques et Appliquées',
            'Sciences expérimentales' => 'Sciences Expérimentales',
            'Économie et gestion'     => 'Économie et Gestion',
            'Technique'               => 'Technologie',
            'Informatique'            => 'Informatique',
            'Lettres'                 => 'Lettres et Sciences Humaines',
            'Sport'                   => 'Sport',
        ];

        $domaineActuel = $mapBacDomaine[$sectionActuelle] ?? null;
        $domaineNouveau = $mapBacDomaine[$nouvelleSection] ?? null;

        $queryActuel = \App\Models\Filiere::where('sdo_2025', '<=', $scoreActuel);
        if ($domaineActuel) {
            $queryActuel->where('domaine', $domaineActuel);
        }
        $formationsActuellesCount = $queryActuel->count();

        $queryNouveau = \App\Models\Filiere::where('sdo_2025', '<=', $scoreNouveau);
        if ($domaineNouveau) {
            $queryNouveau->where('domaine', $domaineNouveau);
        }
        $formationsNouvellesCount = $queryNouveau->count();

        return [
            'section_actuelle' => $sectionActuelle,
            'section_nouvelle' => $nouvelleSection,
            'score_actuel'     => $scoreActuel,
            'score_nouveau'    => $scoreNouveau,
            'delta'            => $delta,
            'delta_pct'        => $scoreActuel > 0 ? round(($delta / $scoreActuel) * 100, 1) : 0,
            'niveau_actuel'    => $this->getNiveauScore($scoreActuel),
            'niveau_nouveau'   => $this->getNiveauScore($scoreNouveau),
            'formations_actuelles' => $formationsActuellesCount,
            'formations_nouvelles' => $formationsNouvellesCount,
            'verdict' => $delta > 0 ? 'favorable' : ($delta < 0 ? 'défavorable' : 'neutre'),
        ];
    }

    // ══════════════════════════════════════════════════════════════
    //  MODULE 3 — Filière alternative
    // ══════════════════════════════════════════════════════════════

    public function simulerFiliereAlternative(array $formationIds): array
    {
        $formations = Formation::with('specialite')
            ->whereIn('id', $formationIds)
            ->get();

        return $formations->map(function ($f) {
            $dureeNum = (int) filter_var($f->duree, FILTER_SANITIZE_NUMBER_INT);
            $difficulte = $this->estimerDifficulte($f);

            return [
                'id'             => $f->id,
                'nom'            => $f->nom,
                'icon'           => $f->icon,
                'etablissement'  => $f->etablissement,
                'ville'          => $f->ville,
                'niveau'         => $f->niveau,
                'duree'          => $f->duree,
                'duree_num'      => $dureeNum,
                'salaire_min'    => $f->salaire_min,
                'salaire_max'    => $f->salaire_max,
                'domaine'        => $f->specialite?->domaine ?? 'Général',
                'score_matching' => $f->score_matching,
                'difficulte'     => $difficulte,
                'insertion'      => $this->estimerInsertion($f),
                'radar' => [
                    'compatibilite' => $f->score_matching ?? 50,
                    'salaire'       => min(100, round((($f->salaire_max ?? 1500) / 3000) * 100)),
                    'rapidite'      => max(10, 100 - ($dureeNum * 18)),
                    'insertion'     => $this->estimerInsertion($f),
                    'difficulte'    => $difficulte,
                ],
            ];
        })->toArray();
    }



    // ══════════════════════════════════════════════════════════════
    //  MODULE 5 — Secteurs & Employabilité
    // ══════════════════════════════════════════════════════════════

    public function getSecteursEmployabilite(): array
    {
        $secteurs = self::SECTEURS;

        // Tri par taux d'insertion décroissant
        uasort($secteurs, fn($a, $b) => $b['insertion'] <=> $a['insertion']);

        return array_map(function ($s, $key) {
            $projection5ans = min(100, round($s['insertion'] + ($s['croissance'] * 2.5)));
            return array_merge($s, [
                'code'            => $key,
                'projection_5ans' => $projection5ans,
                'risque'          => $s['saturation'] > 55 ? 'élevé' : ($s['saturation'] > 35 ? 'modéré' : 'faible'),
                'tendance'        => $s['croissance'] >= 8 ? 'hausse_forte' : ($s['croissance'] >= 5 ? 'hausse' : 'stable'),
            ]);
        }, $secteurs, array_keys($secteurs));
    }

    // ══════════════════════════════════════════════════════════════
    //  MODULE 6 — Salaires & ROI
    // ══════════════════════════════════════════════════════════════

    public function calculerROI(?int $formationId = null, string $niveau = 'licence'): array
    {
        $grille  = self::SALAIRES[$niveau] ?? self::SALAIRES['licence'];
        $formation = $formationId ? Formation::find($formationId) : null;

        $dureeEtudes = match($niveau) {
            'licence' => 3, 'master' => 5, 'ingenieur' => 5, 'doctorat' => 8, default => 3,
        };
        $coutAnnuel = 2400; // TND — coût moyen études en Tunisie
        $coutTotal  = $coutAnnuel * $dureeEtudes;

        $salaireDebut = $formation ? ($formation->salaire_min ?? $grille['min']) : $grille['min'];
        $salaireMax   = $formation ? ($formation->salaire_max ?? $grille['max']) : $grille['max'];

        // Évolution salariale sur 10 ans (courbe progressive)
        $evolution = [];
        for ($i = 0; $i <= 10; $i++) {
            $progression = 1 + ($i * 0.08); // +8% par an en moyenne
            $evolution[] = [
                'annee'  => $i,
                'salaire'=> round($salaireDebut * $progression),
            ];
        }

        $revenus5ans  = 0;
        $revenus10ans = 0;
        for ($i = 1; $i <= 10; $i++) {
            $salaire = $salaireDebut * (1 + ($i * 0.08)) * 12;
            if ($i <= 5) $revenus5ans += $salaire;
            $revenus10ans += $salaire;
        }

        return [
            'niveau'         => $niveau,
            'duree_etudes'   => $dureeEtudes,
            'cout_total'     => $coutTotal,
            'salaire_debut'  => $salaireDebut,
            'salaire_max'    => $salaireMax,
            'salaire_5ans'   => $grille['apres5'],
            'salaire_10ans'  => $grille['apres10'],
            'revenus_5ans'   => round($revenus5ans),
            'revenus_10ans'  => round($revenus10ans),
            'roi_5ans'       => round((($revenus5ans - $coutTotal) / max(1, $coutTotal)) * 100),
            'roi_10ans'      => round((($revenus10ans - $coutTotal) / max(1, $coutTotal)) * 100),
            'breakeven_mois' => $salaireDebut > 0 ? ceil($coutTotal / $salaireDebut) : 0,
            'evolution'      => $evolution,
            'formation'      => $formation ? ['nom' => $formation->nom, 'icon' => $formation->icon] : null,
        ];
    }

    // ══════════════════════════════════════════════════════════════
    //  MODULE 7 — Compatibilité Carrière
    // ══════════════════════════════════════════════════════════════

    public function calculerCompatibiliteCarriere(User $user): array
    {
        $dernierProfil = $user->dernierProfilRiasec;
        $riasecScores = $dernierProfil ? $dernierProfil->scores_par_dimension : null;

        if (!$riasecScores || !is_array($riasecScores)) {
            return ['has_profile' => false, 'message' => 'Passez le test psychométrique pour débloquer cette analyse.'];
        }

        // Trouver les 2 types RIASEC dominants
        arsort($riasecScores);
        $topTypes = array_slice(array_keys($riasecScores), 0, 2);

        // Calculer la compatibilité par secteur
        $compatibilites = [];
        foreach (self::SECTEURS as $code => $secteur) {
            $score = 0;
            foreach ($topTypes as $type) {
                $domaines = self::RIASEC_DOMAINES[$type] ?? [];
                if (in_array($code, $domaines)) {
                    $score += ($riasecScores[$type] ?? 0) * 1.5;
                }
            }
            // Normaliser sur 100
            $score = min(100, round($score));

            $compatibilites[] = array_merge($secteur, [
                'code'          => $code,
                'compatibilite' => $score,
                'recommande'    => $score >= 50,
            ]);
        }

        // Trier par compatibilité
        usort($compatibilites, fn($a, $b) => $b['compatibilite'] <=> $a['compatibilite']);

        return [
            'has_profile'    => true,
            'riasec_code'    => implode('', $topTypes),
            'riasec_scores'  => $riasecScores,
            'top_secteurs'   => array_slice($compatibilites, 0, 5),
            'all_secteurs'   => $compatibilites,
        ];
    }

    // ══════════════════════════════════════════════════════════════
    //  HELPERS
    // ══════════════════════════════════════════════════════════════

    private function getNiveauScore(float $score): string
    {
        if ($score >= 160) return 'excellent';
        if ($score >= 130) return 'bon';
        if ($score >= 100) return 'moyen';
        return 'faible';
    }

    private function estimerDifficulte(Formation $f): int
    {
        $score = 50;
        if (str_contains(strtolower($f->niveau ?? ''), 'ingénieur')) $score += 25;
        if (str_contains(strtolower($f->niveau ?? ''), 'master'))    $score += 15;
        if (($f->score_matching ?? 50) > 70) $score += 10;
        return min(100, $score);
    }

    private function estimerInsertion(Formation $f): int
    {
        $base = 60;
        if ($f->salaire_max && $f->salaire_max > 2000) $base += 15;
        if (str_contains(strtolower($f->nom ?? ''), 'ingénieur')) $base += 10;
        if (str_contains(strtolower($f->specialite?->domaine ?? ''), 'informatique')) $base += 12;
        return min(95, $base);
    }



    /** Expose les niveaux pour le ROI */
    public function getNiveauxDisponibles(): array
    {
        return ['licence' => 'Licence (3 ans)', 'master' => 'Master (5 ans)', 'ingenieur' => 'Ingénieur (5 ans)', 'doctorat' => 'Doctorat (8 ans)'];
    }
}
