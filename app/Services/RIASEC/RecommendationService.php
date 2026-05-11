<?php

namespace App\Services\RIASEC;

use App\Models\Filiere;
use App\Models\Profile;

class RecommendationService
{
    /**
     * Calcule les 3 meilleures recommandations de filières basées sur le profil de l'étudiant.
     *
     * @param string $studentRiasec Trigramme RIASEC de l'étudiant (ex: "IRC")
     * @param float|null $studentScoreFg Score FG de l'étudiant (facultatif, par défaut 120)
     * @return array Top 3 filières avec leurs scores détaillés et explications.
     */
    public function getTopRecommendations(string $studentRiasec, ?float $studentScoreFg = null): array
    {
        $filieres = Filiere::whereNotNull('code_riasec')->get();
        $studentScoreFg = $studentScoreFg ?? 120.0; // Valeur moyenne par défaut
        
        $scoredFilieres = [];

        foreach ($filieres as $filiere) {
            // 1. Score Académique (50%)
            $filiereSdo = $filiere->sdo_actuel ?? 100.0;
            $scoreAcademique = $this->calculateAcademicScore($studentScoreFg, $filiereSdo);

            // 2. Score Psychologique (30%) - Indice Iachan
            $scorePsychologique = $this->calculateIachanIndex($studentRiasec, $filiere->code_riasec);

            // 3. Score Marché (20%)
            $scoreMarche = $this->calculateMarketScore($filiere);

            // SRF Global
            $srf = ($scoreAcademique * 0.5) + ($scorePsychologique * 0.3) + ($scoreMarche * 0.2);

            $scoredFilieres[] = [
                'filiere'             => $filiere,
                'Score_Academique'    => round($scoreAcademique, 1),
                'Score_Psychologique' => round($scorePsychologique, 1),
                'Score_Marche'        => round($scoreMarche, 1),
                'SRF'                 => round($srf, 1),
                'SDO_2025'            => $filiere->sdo_2025,
                'Nom_Filiere'         => $filiere->nom_filiere,
                'Universite'          => $filiere->universite,
                'Etablissement'       => $filiere->etablissement,
                'RIASEC'              => strtoupper($filiere->code_riasec),
                'Taux_Employabilite'  => $filiere->taux_employabilite_pct,
                'Croissance_Domaine'  => $filiere->croissance_domaine ? ($filiere->croissance_domaine * 100).'%' : null,
                'explanation'         => $this->generateExplanation($filiere, $studentRiasec),
            ];
        }

        // Trier par SRF décroissant
        usort($scoredFilieres, fn($a, $b) => $b['SRF'] <=> $a['SRF']);

        return array_slice($scoredFilieres, 0, 3);
    }

    /**
     * Score Académique : Pénalité progressive si le FG de l'étudiant est trop inférieur au SDO.
     */
    private function calculateAcademicScore(float $studentFg, float $filiereSdo): float
    {
        $diff = $studentFg - $filiereSdo;
        if ($diff >= 0) {
            return min(100, 85 + ($diff * 0.5)); // Bonus plafonné
        } else {
            // Si la différence est négative (l'étudiant a moins que le SDO)
            // Pénalité douce jusqu'à -10 points, puis pénalité forte
            if ($diff >= -10) {
                return 80 - (abs($diff) * 1.5);
            } else {
                return max(0, 80 - 15 - ((abs($diff) - 10) * 3));
            }
        }
    }

    /**
     * Score Psychologique : Indice d'accord de Iachan entre le profil étudiant et filière.
     * Basé sur les 3 premières lettres.
     */
    private function calculateIachanIndex(string $studentCode, string $filiereCode): float
    {
        $student = str_split(strtoupper(substr(trim($studentCode), 0, 3)));
        $filiere = str_split(strtoupper(substr(trim($filiereCode), 0, 3)));

        if (count($student) < 1 || count($filiere) < 1) return 50.0; // Neutre si données manquantes

        $score = 0;

        // Poids selon la position (1er: 22, 2ème: 10, 3ème: 4)
        $weights = [22, 10, 4];
        
        foreach ($student as $i => $sLetter) {
            $pos = array_search($sLetter, $filiere);
            if ($pos !== false) {
                // Match exact (même position)
                if ($pos == $i) {
                    $score += $weights[$i];
                } 
                // Match partiel (position différente)
                else {
                    $score += $weights[$i] / 2;
                }
            }
        }

        // Score max théorique = 22 + 10 + 4 = 36
        $normalized = ($score / 36) * 100;
        return max(0, min(100, $normalized));
    }

    /**
     * Score Marché : Moyenne des 3 métriques disponibles dans la base.
     */
    private function calculateMarketScore(Filiere $filiere): float
    {
        $employability = $filiere->taux_employabilite ?? 0.5;
        $growth = $filiere->croissance_domaine ?? 0.5;
        $alignment = $filiere->alignment_national ?? 0.5;

        // On donne plus de poids à l'employabilité
        $score = ($employability * 0.5) + ($growth * 0.3) + ($alignment * 0.2);
        
        return $score * 100;
    }

    /**
     * Génère une explication motivante pour la recommandation.
     */
    private function generateExplanation(Filiere $filiere, string $studentRiasec): string
    {
        $matchLetters = count(array_intersect(
            str_split(strtoupper(substr($studentRiasec, 0, 3))),
            str_split(strtoupper(substr($filiere->code_riasec, 0, 3)))
        ));

        $sentences = [];

        if ($matchLetters >= 2) {
            $sentences[] = "Cette filière correspond parfaitement à ta personnalité et tes intérêts naturels ($filiere->code_riasec).";
        } else {
            $sentences[] = "Une voie intéressante qui saura valoriser tes atouts dans un environnement dynamique.";
        }

        if (($filiere->taux_employabilite ?? 0) >= 0.8) {
            $sentences[] = "Elle offre de très fortes perspectives d'employabilité sur le marché actuel.";
        }

        return implode(' ', $sentences);
    }
}
