<?php

namespace App\Services;

use App\Models\Formation;

/**
 * ScoreFGService — Calcule le Score Formule Globale du BAC tunisien.
 *
 * Ce service implémente les formules officielles tunisiennes sans dépendance IA.
 * Utilisé pour le Simulateur What-If (calcul instantané côté serveur).
 */
class ScoreFGService
{
    /**
     * Formules officielles par section BAC.
     * Chaque entrée = [coefficient de chaque matière clé]
     * Format : ['matiere' => coefficient]
     */
    private const FORMULES = [
        'Mathématiques' => [
            'mg'   => 4,
            'math' => 2,
            'sp'   => 1.5,
            'svt'  => 0.5,
            'fr'   => 1,
            'ang'  => 1,
        ],
        'Sciences expérimentales' => [
            'mg'   => 4,
            'math' => 1,
            'sp'   => 1.5,
            'svt'  => 1.5,
            'fr'   => 1,
            'ang'  => 1,
        ],
        'Économie et gestion' => [
            'mg'   => 4,
            'eco'  => 1.5,
            'gest' => 1.5,
            'math' => 0.5,
            'hg'   => 0.5,
            'fr'   => 1,
            'ang'  => 1,
        ],
        'Technique' => [
            'mg'   => 4,
            'tech' => 1.5,
            'math' => 1.5,
            'sp'   => 1,
            'fr'   => 1,
            'ang'  => 1,
        ],
        'Informatique' => [
            'mg'   => 4,
            'algo' => 1.5,
            'sp'   => 0.5,
            'sti'  => 0.5,
            'fr'   => 1,
            'ang'  => 1,
        ],
        'Lettres' => [
            'mg'   => 4,
            'ar'   => 1.5,
            'philo'=> 1.5,
            'hg'   => 1,
            'fr'   => 1,
            'ang'  => 1,
        ],
        'Sport' => [
            'mg'     => 4,
            'bio'    => 1.5,
            'sport'  => 1,
            'ep'     => 0.5,
            'sp'     => 0.5,
            'ph'     => 0.5,
            'fr'     => 1,
            'ang'    => 1,
        ],
    ];

    /**
     * Labels affichables par matière (pour les formulaires).
     */
    public const MATIERES_LABELS = [
        'mg'    => 'Moyenne Générale',
        'math'  => 'Mathématiques',
        'sp'    => 'Sciences Physiques',
        'svt'   => 'SVT (Sciences de la Vie)',
        'fr'    => 'Français',
        'ang'   => 'Anglais',
        'eco'   => 'Économie',
        'gest'  => 'Gestion',
        'hg'    => 'Histoire-Géographie',
        'tech'  => 'Technologie',
        'algo'  => 'Algorithmique',
        'sti'   => 'Systèmes & Technologies',
        'ar'    => 'Arabe',
        'philo' => 'Philosophie',
        'bio'   => 'Sciences Biologiques',
        'sport' => 'Spécialité Sport',
        'ep'    => 'Éducation Physique',
        'ph'    => 'Physique',
    ];

    /**
     * Calcule le Score FG pour une section donnée.
     *
     * @param string $section  Section du BAC
     * @param float  $mg       Moyenne générale
     * @param array  $notes    ['matiere' => note] (sans MG)
     * @return float           Score FG calculé
     * @throws \InvalidArgumentException
     */
    public function calculer(string $section, float $mg, array $notes): float
    {
        if (!isset(self::FORMULES[$section])) {
            throw new \InvalidArgumentException("Section BAC inconnue : {$section}");
        }

        $formule = self::FORMULES[$section];
        $score   = 0.0;

        foreach ($formule as $matiere => $coef) {
            if ($matiere === 'mg') {
                $score += $mg * $coef;
            } else {
                $note = (float) ($notes[$matiere] ?? 0);
                $score += $note * $coef;
            }
        }

        return round($score, 2);
    }

    /**
     * Retourne les matières requises pour une section donnée (sans MG).
     *
     * @param string $section
     * @return array  ['code' => 'label']
     */
    public function getMatieresSection(string $section): array
    {
        if (!isset(self::FORMULES[$section])) {
            return [];
        }

        $matieres = [];
        foreach (self::FORMULES[$section] as $code => $coef) {
            if ($code === 'mg') continue;
            $matieres[$code] = [
                'label' => self::MATIERES_LABELS[$code] ?? $code,
                'coef'  => $coef,
            ];
        }

        return $matieres;
    }

    /**
     * Retourne les formations accessibles pour un score FG donné.
     * Se base sur le champ score_sdo_min de la table formations si disponible,
     * sinon utilise une approximation par niveau.
     *
     * @param float $scoreFg
     * @param int   $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFormationsAccessibles(float $scoreFg, int $limit = 10)
    {
        // Approximation : score_matching est stocké en % (0-100)
        // On convertit le score FG (0~200) en % pour comparer
        $scorePct = min(100, round(($scoreFg / 200) * 100));

        return Formation::with('specialite')
            ->where('score_matching', '<=', $scorePct + 15)
            ->orderBy('score_matching', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Retourne toutes les sections BAC disponibles.
     */
    public function getSections(): array
    {
        return array_keys(self::FORMULES);
    }
}
