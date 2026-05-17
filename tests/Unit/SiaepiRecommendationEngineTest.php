<?php

namespace Tests\Unit;

use App\Services\SiaepiRecommendationEngine;
use Tests\TestCase;

class SiaepiRecommendationEngineTest extends TestCase
{
    /**
     * Teste qu'un excellent profil mathématique avec 18 de moyenne (score_fg = 180)
     * reçoit des recommandations hautement logiques (Informatique, Ingénierie, Mathématiques)
     * et n'a JAMAIS de filières paramédicales (Kiné, Infirmier, Sage-femme) dans son Top-6.
     */
    public function test_excellent_math_student_gets_proper_recommendations()
    {
        $engine = new SiaepiRecommendationEngine();

        // Profil d'un excellent élève de BAC Mathématiques (Moyenne 18.0 -> score_fg ≈ 180)
        // Code Holland : IAS (Investigateur, Artistique, Social)
        // Des scores GATB très élevés reflétant un esprit logique/mathématique exceptionnel
        $profilExcellentMath = [
            'score_fg'                  => 180.0,
            'section_bac'               => 'Mathématiques',
            'filiere_etudiant_actuelle' => 'Mathématiques',
            'texte_psycho'              => 'analyse logique mathématique recherche sciences',
            'vecteur_psychometrique'    => [
                'R' => 0.5,
                'I' => 0.9, // Très investigateur (scientifique)
                'A' => 0.4,
                'S' => 0.3, // Faible en soins/social
                'E' => 0.6,
                'C' => 0.8, // Organisé, rigoureux
            ],
            'gatb_scores' => [
                'G' => 95, // Général (très élevé)
                'V' => 80, // Verbal
                'N' => 98, // Numérique (mathématique pur)
                'S' => 90, // Spatial
            ],
            'code_holland' => 'ICA',
            'big_five' => [
                'O' => 0.8, // Ouverture
                'C' => 0.9, // Rigueur
                'E' => 0.6,
                'A' => 0.5,
                'N' => 0.2,
            ],
            'valeurs' => [
                'Sec' => 0.6,
                'Ach' => 0.9, // Accomplissement élevé
                'Ben' => 0.5,
                'Aut' => 0.8, // Autonomie
            ],
            'interests' => [
                'MED'   => 0.2, // Pas d'intérêt médical
                'ENG'   => 0.9, // Intérêt ingénierie
                'INFO'  => 0.9, // Intérêt informatique
                'DROIT' => 0.3,
                'ECO'   => 0.6,
                'EDU'   => 0.4,
                'ART'   => 0.3,
                'LTR'   => 0.2,
                'SOC'   => 0.3,
                'SPO'   => 0.2,
                'ARCHI' => 0.5,
            ]
        ];

        $res = $engine->recommend($profilExcellentMath, 6);

        $this->assertArrayNotHasKey('error', $res, 'Le moteur ne doit pas retourner d\'erreur.');
        $this->assertNotEmpty($res['recommandations'], 'Des recommandations doivent être générées.');

        // Récupérer le Top 6 des filières recommandées
        $recommandations = $res['recommandations'];

        $filiereNames = array_map(fn($f) => mb_strtolower($f['Nom_Filiere']), $recommandations);

        // Aucun terme paramédical (Kiné, Infirmier, Sage-femme) ne doit figurer dans le Top 6
        foreach ($filiereNames as $name) {
            $this->assertStringNotContainsString('kiné', $name, "Le Top-6 ne doit pas contenir de Kinésithérapie.");
            $this->assertStringNotContainsString('infirmier', $name, "Le Top-6 ne doit pas contenir de Sciences Infirmières.");
            $this->assertStringNotContainsString('sage-femme', $name, "Le Top-6 ne doit pas contenir de Sage-femme.");
        }

        // Au moins un choix majeur correspondant aux forces d'un élève excellent en Maths
        // (ex: Informatique, Mathématiques, Ingénierie, Sciences)
        $hasMathOrTech = false;
        foreach ($filiereNames as $name) {
            if (str_contains($name, 'inform') || str_contains($name, 'ingéni') || str_contains($name, 'math') || str_contains($name, 'génie')) {
                $hasMathOrTech = true;
                break;
            }
        }
        $this->assertTrue($hasMathOrTech, "Le Top-6 doit inclure des options logiques en Informatique, Ingénierie ou Mathématiques.");
    }
}
