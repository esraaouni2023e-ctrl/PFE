<?php

namespace Tests\Unit;

use App\Services\SiaepiRecommendationEngine;
use App\Models\Filiere;
use App\Models\FiliereProfile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SiaepiRecommendationEngineTest extends TestCase
{
    use RefreshDatabase;
    protected bool $seed = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedMockData();
    }

    private function seedMockData(): void
    {
        $filieres = [
            [
                'code_filiere' => 'INF001',
                'nom_filiere' => 'Licence en Informatique',
                'universite' => 'Université de Tunis',
                'etablissement' => 'FST',
                'sdo_2023' => 135,
                'sdo_2024' => 140,
                'sdo_2025' => 142,
                'domaine' => 'informatique',
                'code_riasec' => 'IRC',
                'taux_employabilite' => 'Très élevé',
                'croissance_domaine' => 'Très forte',
                'type_bac' => 'informatique',
                'profile' => [
                    'riasec_r' => 0.8, 'riasec_i' => 1.0, 'riasec_a' => 0.2, 'riasec_s' => 0.2, 'riasec_e' => 0.2, 'riasec_c' => 0.6,
                    'gatb_g_required' => 55, 'gatb_v_required' => 50, 'gatb_n_required' => 60, 'gatb_s_required' => 60,
                    'employability_index' => 0.95, 'difficulty_level' => 7, 'stress_tolerance' => 6,
                    'job_demand' => 0.95, 'salary' => 0.90, 'internships' => 0.95,
                    'big5_openness' => 0.70, 'big5_conscientiousness' => 0.80, 'big5_extraversion' => 0.00, 'big5_agreeableness' => 0.00, 'big5_neuroticism' => -0.40,
                ]
            ],
            [
                'code_filiere' => 'GC001',
                'nom_filiere' => 'Licence en Génie Civil',
                'universite' => 'Université de Carthage',
                'etablissement' => 'ENIT',
                'sdo_2023' => 145, 'sdo_2024' => 150, 'sdo_2025' => 152,
                'domaine' => 'technique',
                'code_riasec' => 'RIC',
                'taux_employabilite' => 'Elevé',
                'croissance_domaine' => 'Forte',
                'type_bac' => 'technique',
                'profile' => [
                    'riasec_r' => 1.0, 'riasec_i' => 0.8, 'riasec_a' => 0.2, 'riasec_s' => 0.2, 'riasec_e' => 0.2, 'riasec_c' => 0.6,
                    'gatb_g_required' => 60, 'gatb_v_required' => 50, 'gatb_n_required' => 60, 'gatb_s_required' => 60,
                    'employability_index' => 0.85, 'difficulty_level' => 8, 'stress_tolerance' => 7,
                    'job_demand' => 0.85, 'salary' => 0.80, 'internships' => 0.85,
                    'big5_openness' => 0.60, 'big5_conscientiousness' => 0.90, 'big5_extraversion' => 0.00, 'big5_agreeableness' => 0.00, 'big5_neuroticism' => 0.00,
                ]
            ],
            [
                'code_filiere' => 'MPI001',
                'nom_filiere' => 'Filière préparatoire intégrée: Maths-Physique et Informatique',
                'universite' => 'Université de Tunis',
                'etablissement' => 'IPEIT',
                'sdo_2023' => 155, 'sdo_2024' => 160, 'sdo_2025' => 162,
                'domaine' => 'sciences',
                'code_riasec' => 'IRC',
                'taux_employabilite' => 'Très élevé',
                'croissance_domaine' => 'Très forte',
                'type_bac' => 'mathematiques',
                'profile' => [
                    'riasec_r' => 0.8, 'riasec_i' => 1.0, 'riasec_a' => 0.2, 'riasec_s' => 0.2, 'riasec_e' => 0.2, 'riasec_c' => 0.6,
                    'gatb_g_required' => 65, 'gatb_v_required' => 50, 'gatb_n_required' => 60, 'gatb_s_required' => 55,
                    'employability_index' => 0.95, 'difficulty_level' => 8, 'stress_tolerance' => 7,
                    'job_demand' => 0.95, 'salary' => 0.90, 'internships' => 0.95,
                    'big5_openness' => 0.90, 'big5_conscientiousness' => 0.70, 'big5_extraversion' => 0.00, 'big5_agreeableness' => 0.00, 'big5_neuroticism' => 0.00,
                ]
            ],
            [
                'code_filiere' => 'VET001',
                'nom_filiere' => 'Filière préparatoire intégrée en Ingénierie Vétérinaire',
                'universite' => 'Université de la Manouba',
                'etablissement' => 'ENMV',
                'sdo_2023' => 160, 'sdo_2024' => 165, 'sdo_2025' => 168,
                'domaine' => 'sciences',
                'code_riasec' => 'IRS',
                'taux_employabilite' => 'Elevé',
                'croissance_domaine' => 'Stable',
                'type_bac' => 'sciences experimentales',
                'profile' => [
                    'riasec_r' => 0.8, 'riasec_i' => 1.0, 'riasec_a' => 0.2, 'riasec_s' => 0.6, 'riasec_e' => 0.2, 'riasec_c' => 0.4,
                    'gatb_g_required' => 65, 'gatb_v_required' => 50, 'gatb_n_required' => 60, 'gatb_s_required' => 55,
                    'employability_index' => 0.85, 'difficulty_level' => 8, 'stress_tolerance' => 7,
                    'job_demand' => 0.85, 'salary' => 0.80, 'internships' => 0.85,
                    'big5_openness' => 0.90, 'big5_conscientiousness' => 0.70, 'big5_extraversion' => 0.00, 'big5_agreeableness' => 0.00, 'big5_neuroticism' => 0.00,
                ]
            ],
            [
                'code_filiere' => 'ECO001',
                'nom_filiere' => 'Licence en Sciences Économiques',
                'universite' => 'Université de Tunis El Manar',
                'etablissement' => 'FSEGT',
                'sdo_2023' => 115, 'sdo_2024' => 120, 'sdo_2025' => 122,
                'domaine' => 'economie',
                'code_riasec' => 'CES',
                'taux_employabilite' => 'Modéré',
                'croissance_domaine' => 'Stable',
                'type_bac' => 'economie et gestion',
                'profile' => [
                    'riasec_r' => 0.2, 'riasec_i' => 0.6, 'riasec_a' => 0.2, 'riasec_s' => 0.4, 'riasec_e' => 0.8, 'riasec_c' => 1.0,
                    'gatb_g_required' => 55, 'gatb_v_required' => 55, 'gatb_n_required' => 60, 'gatb_s_required' => 50,
                    'employability_index' => 0.60, 'difficulty_level' => 6, 'stress_tolerance' => 5,
                    'job_demand' => 0.60, 'salary' => 0.60, 'internships' => 0.60,
                    'big5_openness' => 0.00, 'big5_conscientiousness' => 0.70, 'big5_extraversion' => 0.80, 'big5_agreeableness' => 0.00, 'big5_neuroticism' => 0.00,
                ]
            ],
            [
                'code_filiere' => 'ECO002',
                'nom_filiere' => 'Baccalauréat en Administration des Affaires',
                'universite' => 'Université de Tunis',
                'etablissement' => 'ISG',
                'sdo_2023' => 125, 'sdo_2024' => 130, 'sdo_2025' => 132,
                'domaine' => 'economie',
                'code_riasec' => 'ESC',
                'taux_employabilite' => 'Elevé',
                'croissance_domaine' => 'Forte',
                'type_bac' => 'economie et gestion',
                'profile' => [
                    'riasec_r' => 0.2, 'riasec_i' => 0.4, 'riasec_a' => 0.2, 'riasec_s' => 0.6, 'riasec_e' => 1.0, 'riasec_c' => 0.8,
                    'gatb_g_required' => 55, 'gatb_v_required' => 55, 'gatb_n_required' => 60, 'gatb_s_required' => 50,
                    'employability_index' => 0.85, 'difficulty_level' => 6, 'stress_tolerance' => 5,
                    'job_demand' => 0.80, 'salary' => 0.75, 'internships' => 0.80,
                    'big5_openness' => 0.00, 'big5_conscientiousness' => 0.70, 'big5_extraversion' => 0.80, 'big5_agreeableness' => 0.00, 'big5_neuroticism' => 0.00,
                ]
            ],
            [
                'code_filiere' => 'KINE01',
                'nom_filiere' => 'Licence en Kinésithérapie',
                'universite' => 'Université de Sousse',
                'etablissement' => 'ESSTSS',
                'sdo_2023' => 135, 'sdo_2024' => 140, 'sdo_2025' => 142,
                'domaine' => 'sante',
                'code_riasec' => 'SIR',
                'taux_employabilite' => 'Modéré',
                'croissance_domaine' => 'Stable',
                'type_bac' => 'sciences experimentales',
                'profile' => [
                    'riasec_r' => 0.6, 'riasec_i' => 0.8, 'riasec_a' => 0.2, 'riasec_s' => 1.0, 'riasec_e' => 0.2, 'riasec_c' => 0.4,
                    'gatb_g_required' => 65, 'gatb_v_required' => 60, 'gatb_n_required' => 55, 'gatb_s_required' => 55,
                    'employability_index' => 0.60, 'difficulty_level' => 6, 'stress_tolerance' => 6,
                    'job_demand' => 0.60, 'salary' => 0.60, 'internships' => 0.60,
                    'big5_openness' => 0.00, 'big5_conscientiousness' => 0.80, 'big5_extraversion' => 0.00, 'big5_agreeableness' => 0.90, 'big5_neuroticism' => -0.50,
                ]
            ],
            [
                'code_filiere' => 'INF02',
                'nom_filiere' => 'Licence en Sciences Infirmières',
                'universite' => 'Université de Monastir',
                'etablissement' => 'ESSTSSM',
                'sdo_2023' => 125, 'sdo_2024' => 130, 'sdo_2025' => 132,
                'domaine' => 'sante',
                'code_riasec' => 'SIR',
                'taux_employabilite' => 'Très élevé',
                'croissance_domaine' => 'Forte',
                'type_bac' => 'sciences experimentales',
                'profile' => [
                    'riasec_r' => 0.4, 'riasec_i' => 0.8, 'riasec_a' => 0.2, 'riasec_s' => 1.0, 'riasec_e' => 0.2, 'riasec_c' => 0.6,
                    'gatb_g_required' => 65, 'gatb_v_required' => 60, 'gatb_n_required' => 55, 'gatb_s_required' => 55,
                    'employability_index' => 0.95, 'difficulty_level' => 6, 'stress_tolerance' => 7,
                    'job_demand' => 0.90, 'salary' => 0.85, 'internships' => 0.90,
                    'big5_openness' => 0.00, 'big5_conscientiousness' => 0.80, 'big5_extraversion' => 0.00, 'big5_agreeableness' => 0.90, 'big5_neuroticism' => -0.50,
                ]
            ],
            [
                'code_filiere' => 'SF001',
                'nom_filiere' => "Licence d'Obstétrique (Sage-femme)",
                'universite' => 'Université de Sfax',
                'etablissement' => 'ESSTSSS',
                'sdo_2023' => 130, 'sdo_2024' => 135, 'sdo_2025' => 138,
                'domaine' => 'sante',
                'code_riasec' => 'SIR',
                'taux_employabilite' => 'Elevé',
                'croissance_domaine' => 'Stable',
                'type_bac' => 'sciences experimentales',
                'profile' => [
                    'riasec_r' => 0.4, 'riasec_i' => 0.8, 'riasec_a' => 0.2, 'riasec_s' => 1.0, 'riasec_e' => 0.2, 'riasec_c' => 0.6,
                    'gatb_g_required' => 65, 'gatb_v_required' => 60, 'gatb_n_required' => 55, 'gatb_s_required' => 55,
                    'employability_index' => 0.85, 'difficulty_level' => 6, 'stress_tolerance' => 7,
                    'job_demand' => 0.90, 'salary' => 0.85, 'internships' => 0.90,
                    'big5_openness' => 0.00, 'big5_conscientiousness' => 0.80, 'big5_extraversion' => 0.00, 'big5_agreeableness' => 0.90, 'big5_neuroticism' => -0.50,
                ]
            ],
            [
                'code_filiere' => 'MED001',
                'nom_filiere' => 'Diplôme de Docteur en Médecine',
                'universite' => 'Université de Tunis El Manar',
                'etablissement' => 'FMT',
                'sdo_2023' => 175, 'sdo_2024' => 180, 'sdo_2025' => 182,
                'domaine' => 'sante',
                'code_riasec' => 'ISR',
                'taux_employabilite' => 'Très élevé',
                'croissance_domaine' => 'Très forte',
                'type_bac' => 'sciences experimentales',
                'profile' => [
                    'riasec_r' => 0.6, 'riasec_i' => 1.0, 'riasec_a' => 0.2, 'riasec_s' => 0.8, 'riasec_e' => 0.4, 'riasec_c' => 0.8,
                    'gatb_g_required' => 65, 'gatb_v_required' => 60, 'gatb_n_required' => 55, 'gatb_s_required' => 55,
                    'employability_index' => 0.95, 'difficulty_level' => 9, 'stress_tolerance' => 9,
                    'job_demand' => 0.90, 'salary' => 0.85, 'internships' => 0.90,
                    'big5_openness' => 0.00, 'big5_conscientiousness' => 0.80, 'big5_extraversion' => 0.00, 'big5_agreeableness' => 0.90, 'big5_neuroticism' => -0.50,
                ]
            ],
            [
                'code_filiere' => 'PHAR01',
                'nom_filiere' => 'Diplôme de Docteur en Pharmacie',
                'universite' => 'Université de Monastir',
                'etablissement' => 'FPM',
                'sdo_2023' => 165, 'sdo_2024' => 170, 'sdo_2025' => 172,
                'domaine' => 'sante',
                'code_riasec' => 'ISR',
                'taux_employabilite' => 'Elevé',
                'croissance_domaine' => 'Forte',
                'type_bac' => 'sciences experimentales',
                'profile' => [
                    'riasec_r' => 0.6, 'riasec_i' => 1.0, 'riasec_a' => 0.2, 'riasec_s' => 0.6, 'riasec_e' => 0.4, 'riasec_c' => 0.8,
                    'gatb_g_required' => 65, 'gatb_v_required' => 60, 'gatb_n_required' => 55, 'gatb_s_required' => 55,
                    'employability_index' => 0.85, 'difficulty_level' => 8, 'stress_tolerance' => 8,
                    'job_demand' => 0.90, 'salary' => 0.85, 'internships' => 0.90,
                    'big5_openness' => 0.00, 'big5_conscientiousness' => 0.80, 'big5_extraversion' => 0.00, 'big5_agreeableness' => 0.90, 'big5_neuroticism' => -0.50,
                ]
            ],
            [
                'code_filiere' => 'DENT01',
                'nom_filiere' => 'Diplôme de Docteur en Médecine Dentaire',
                'universite' => 'Université de Monastir',
                'etablissement' => 'FMDM',
                'sdo_2023' => 170, 'sdo_2024' => 172, 'sdo_2025' => 174,
                'domaine' => 'sante',
                'code_riasec' => 'ISR',
                'taux_employabilite' => 'Elevé',
                'croissance_domaine' => 'Stable',
                'type_bac' => 'sciences experimentales',
                'profile' => [
                    'riasec_r' => 0.6, 'riasec_i' => 1.0, 'riasec_a' => 0.2, 'riasec_s' => 0.6, 'riasec_e' => 0.4, 'riasec_c' => 0.8,
                    'gatb_g_required' => 65, 'gatb_v_required' => 60, 'gatb_n_required' => 55, 'gatb_s_required' => 55,
                    'employability_index' => 0.85, 'difficulty_level' => 8, 'stress_tolerance' => 8,
                    'job_demand' => 0.90, 'salary' => 0.85, 'internships' => 0.90,
                    'big5_openness' => 0.00, 'big5_conscientiousness' => 0.80, 'big5_extraversion' => 0.00, 'big5_agreeableness' => 0.90, 'big5_neuroticism' => -0.50,
                ]
            ],
            [
                'code_filiere' => 'BIO001',
                'nom_filiere' => 'Licence en Biologie et Environnement',
                'universite' => 'Université de Carthage',
                'etablissement' => 'FSB',
                'sdo_2023' => 140, 'sdo_2024' => 145, 'sdo_2025' => 148,
                'domaine' => 'sciences',
                'code_riasec' => 'IRS',
                'taux_employabilite' => 'Modéré',
                'croissance_domaine' => 'Stable',
                'type_bac' => 'sciences experimentales',
                'profile' => [
                    'riasec_r' => 0.6, 'riasec_i' => 1.0, 'riasec_a' => 0.2, 'riasec_s' => 0.4, 'riasec_e' => 0.2, 'riasec_c' => 0.6,
                    'gatb_g_required' => 55, 'gatb_v_required' => 50, 'gatb_n_required' => 55, 'gatb_s_required' => 50,
                    'employability_index' => 0.60, 'difficulty_level' => 6, 'stress_tolerance' => 5,
                    'job_demand' => 0.60, 'salary' => 0.55, 'internships' => 0.60,
                    'big5_openness' => 0.70, 'big5_conscientiousness' => 0.80, 'big5_extraversion' => 0.00, 'big5_agreeableness' => 0.00, 'big5_neuroticism' => 0.00,
                ]
            ]
        ];

        foreach ($filieres as $data) {
            $profileData = $data['profile'];
            unset($data['profile']);

            $f = Filiere::create($data);
            $profileData['code_filiere'] = $f->code_filiere;
            $profileData['nom_filiere'] = $f->nom_filiere;
            $profileData['domaine'] = $f->domaine;
            $profileData['description'] = "Description de " . $f->nom_filiere;
            FiliereProfile::create($profileData);
        }
    }

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

    /**
     * Teste le filtre de santé probabiliste (HealthRisk)
     */
    public function test_health_risk_sigmoid_filter()
    {
        $engine = new SiaepiRecommendationEngine();

        // Profil avec SVT très faible (4.0/20) et GATB G très faible (40/100)
        // Ceci devrait générer un HealthRisk très élevé > 0.85 et exclure les filières de santé
        $profilLowSvtAndGatb = [
            'score_fg'                  => 100.0,
            'section_bac'               => 'Sciences expérimentales',
            'filiere_etudiant_actuelle' => 'Sciences expérimentales',
            'notes_matieres'            => ['svt' => 4.0],
            'vecteur_psychometrique'    => ['R'=>0.5,'I'=>0.8,'A'=>0.4,'S'=>0.9,'E'=>0.4,'C'=>0.5],
            'gatb_scores'               => ['G'=>40, 'V'=>50, 'N'=>40, 'S'=>50],
            'code_holland'              => 'ISR',
            'big_five'                  => ['O'=>0.5, 'C'=>0.5, 'E'=>0.5, 'A'=>0.5, 'N'=>0.0],
            'valeurs'                   => ['Sec'=>0.5, 'Ach'=>0.5, 'Ben'=>0.5, 'Aut'=>0.5],
            'interests'                 => ['MED' => 0.9] // Intérêt élevé mais limité par la santé
        ];

        $res = $engine->recommend($profilLowSvtAndGatb, 10);
        $this->assertNotEmpty($res);

        // Vérifier que le top optimal ne contient aucune filière médicale/santé
        $allRecs = array_merge($res['recommandations'], $res['accessibles'], $res['securite']);
        foreach ($allRecs as $f) {
            $name = mb_strtolower($f['Nom_Filiere']);
            $this->assertStringNotContainsString('médec', $name, "Un étudiant à risque de santé élevé ne doit pas être recommandé en Médecine.");
            $this->assertStringNotContainsString('infirm', $name, "Un étudiant à risque de santé élevé ne doit pas être recommandé en Soins Infirmiers.");
        }
    }

    /**
     * Teste le calcul de l'Access Score asymétrique stable.
     */
    public function test_sdo_access_score_tanh_bonus()
    {
        $engine = new SiaepiRecommendationEngine();

        // Tester le cas FG >= SDO : Access = 1 / (1 + e^(-x)) where x = (FG - SDO)/15
        // Pour FG >= SDO, x >= 0, donc AccessScore >= 0.5
        $profil = [
            'score_fg'                  => 180.0,
            'section_bac'               => 'Mathématiques',
            'filiere_etudiant_actuelle' => 'Mathématiques',
            'vecteur_psychometrique'    => ['R'=>0.5,'I'=>0.9,'A'=>0.4,'S'=>0.3,'E'=>0.6,'C'=>0.8],
            'gatb_scores'               => ['G'=>95, 'V'=>80, 'N'=>98, 'S'=>90],
            'code_holland'              => 'ICA',
            'big_five'                  => ['O'=>0.8, 'C'=>0.9, 'E'=>0.6, 'A'=>0.5, 'N'=>0.2],
            'valeurs'                   => ['Sec'=>0.6, 'Ach'=>0.9, 'Ben'=>0.5, 'Aut'=>0.8],
            'interests'                 => ['INFO' => 0.9]
        ];

        $res = $engine->recommend($profil, 6);
        $this->assertNotEmpty($res['recommandations']);

        foreach ($res['recommandations'] as $f) {
            $sdo = max($f['SDO_2025'], $f['SDO_2024'], $f['SDO_2023']);
            if ($sdo > 0 && 180.0 >= $sdo) {
                // L'accessibilité doit être supérieure ou égale à 0.50 (grâce à la courbe sigmoïde)
                $this->assertGreaterThanOrEqual(0.5, $f['AccessScore'], "L'Access Score pour FG >= SDO doit être >= 0.50 via la sigmoïde stable");
            }
        }
    }

    /**
     * Teste que les filières santé sont exclues si l'étudiant n'a pas d'intérêt médical
     */
    public function test_medical_tracks_are_excluded_without_medical_interest()
    {
        $engine = new SiaepiRecommendationEngine();
        $profil = [
            'score_fg'                  => 185.8,
            'section_bac'               => 'Sciences expérimentales',
            'filiere_etudiant_actuelle' => 'Sciences expérimentales',
            'vecteur_psychometrique'    => ['R'=>0.8,'I'=>0.9,'A'=>0.2,'S'=>0.2,'E'=>0.3,'C'=>0.7],
            'gatb_scores'               => ['G'=>95, 'V'=>85, 'N'=>95, 'S'=>90],
            'code_holland'              => 'IEC',
            'big_five'                  => ['O'=>0.7, 'C'=>0.9, 'E'=>0.5, 'A'=>0.4, 'N'=>0.1],
            'valeurs'                   => ['Sec'=>0.6, 'Ach'=>0.9, 'Ben'=>0.4, 'Aut'=>0.8],
            'interests'                 => ['INFO' => 0.9, 'ENG' => 0.8]
        ];

        $res = $engine->recommend($profil, 10);
        $this->assertNotEmpty($res['recommandations']);

        $allFiliere = array_merge($res['recommandations'], $res['accessibles'], $res['securite'], $res['ambitieuses']);
        foreach ($allFiliere as $f) {
            $name = mb_strtolower($f['Nom_Filiere']);
            $this->assertStringNotContainsString('medec', $name, 'Aucune filière médicale ne doit être recommandée sans intérêt médical explicite.');
            $this->assertStringNotContainsString('infirm', $name, 'Aucune filière infirmière ne doit être recommandée sans intérêt médical explicite.');
            $this->assertStringNotContainsString('sage-femme', $name, 'Aucune filière sage-femme ne doit être recommandée sans intérêt médical explicite.');
            $this->assertStringNotContainsString('pharm', $name, 'Aucune filière pharmacie ne doit être recommandée sans intérêt médical explicite.');
        }
    }

    /**
     * Teste la présence des indicateurs déterministes v8.0.
     */
    public function test_v80_deterministic_indicators_are_computed()
    {
        $engine = new SiaepiRecommendationEngine();
        $profil = [
            'score_fg'                  => 150.0,
            'section_bac'               => 'Informatique',
            'filiere_etudiant_actuelle' => 'Informatique',
            'vecteur_psychometrique'    => ['R'=>0.5,'I'=>0.8,'A'=>0.5,'S'=>0.5,'E'=>0.5,'C'=>0.8],
            'gatb_scores'               => ['G'=>75, 'V'=>70, 'N'=>80, 'S'=>70],
            'code_holland'              => 'ICR',
            'big_five'                  => ['O'=>0.7, 'C'=>0.8, 'E'=>0.5, 'A'=>0.5, 'N'=>0.1],
            'valeurs'                   => ['Sec'=>0.6, 'Ach'=>0.7, 'Ben'=>0.5, 'Aut'=>0.7],
            'interests'                 => ['INFO' => 0.8]
        ];

        $res = $engine->recommend($profil, 5);
        $this->assertNotEmpty($res['recommandations']);

        $top = $res['recommandations'][0];
        $this->assertArrayHasKey('FitScore', $top, "Le resultat doit contenir le FitScore unique.");
        $this->assertArrayHasKey('confidence_flag', $top, "Le resultat doit exposer le flag de confiance deterministe.");
        $this->assertArrayNotHasKey('StabilityIndex', $top, "La couche de stabilite stochastic-like ne doit plus etre exposee.");
        $this->assertArrayNotHasKey('Confidence', $top, "Le score de confiance numerique ne doit plus etre expose.");
        $this->assertEqualsWithDelta(
            0.5 * $top['VocationScore'] + 0.5 * $top['CognitiveScore'],
            $top['FitScore'],
            0.0001
        );
        $this->assertSame('OK', $top['confidence_flag']);
    }

    public function test_missing_gatb_uses_fixed_fallback_and_low_confidence_flag()
    {
        $engine = new SiaepiRecommendationEngine();
        $profil = [
            'score_fg'                  => 150.0,
            'section_bac'               => 'Informatique',
            'filiere_etudiant_actuelle' => 'Informatique',
            'vecteur_psychometrique'    => ['R'=>0.5,'I'=>0.8,'A'=>0.5,'S'=>0.5,'E'=>0.5,'C'=>0.8],
            'code_holland'              => 'ICR',
            'big_five'                  => ['O'=>0.7, 'C'=>0.8, 'E'=>0.5, 'A'=>0.5, 'N'=>0.1],
            'valeurs'                   => ['Sec'=>0.6, 'Ach'=>0.7, 'Ben'=>0.5, 'Aut'=>0.7],
            'interests'                 => ['INFO' => 0.8]
        ];

        $first = $engine->recommend($profil, 5);
        $second = $engine->recommend($profil, 5);

        $this->assertSame('LOW', $first['confidence_flag']);
        $this->assertSame($first['recommandations'], $second['recommandations']);
        $this->assertNotEmpty($first['recommandations']);
        $this->assertSame('LOW', $first['recommandations'][0]['confidence_flag']);
    }

    public function test_psychometric_holland_discrimination()
    {
        $engine = new SiaepiRecommendationEngine();

        // Student profile with IAS dominant RIASEC
        $profil = [
            'score_fg'                  => 150.0,
            'section_bac'               => 'Informatique',
            'vecteur_psychometrique'    => ['R'=>0.1,'I'=>1.0,'A'=>0.8,'S'=>0.6,'E'=>0.2,'C'=>0.1],
            'gatb_scores'               => ['GATB_G'=>70,'GATB_V'=>70,'GATB_N'=>70,'GATB_S'=>70],
            'code_holland'              => 'IAS',
            'big_five'                  => ['O'=>0.7, 'C'=>0.8, 'E'=>0.5, 'A'=>0.5, 'N'=>0.1],
            'valeurs'                   => ['Sec'=>0.6, 'Ach'=>0.7, 'Ben'=>0.5, 'Aut'=>0.7],
            'interests'                 => ['INFO' => 0.8]
        ];

        // Create MOCK filieres with different RIASEC codes: IAS, ISE, SEC, ERC
        $iasF = Filiere::create([
            'code_filiere' => 'MOCK_IAS',
            'nom_filiere' => 'Filiere IAS de pointe',
            'universite' => 'U1',
            'etablissement' => 'E1',
            'sdo_2025' => 120,
            'domaine' => 'sciences',
            'code_riasec' => 'IAS',
        ]);
        $iasF->profile()->create([
            'riasec_r'=>0.1, 'riasec_i'=>1.0, 'riasec_a'=>0.8, 'riasec_s'=>0.6, 'riasec_e'=>0.2, 'riasec_c'=>0.1,
            'gatb_g_required'=>60, 'gatb_v_required'=>60, 'gatb_n_required'=>60, 'gatb_s_required'=>60,
            'nom_filiere' => $iasF->nom_filiere,
            'domaine' => $iasF->domaine,
            'description' => 'Desc IAS',
        ]);

        $iseF = Filiere::create([
            'code_filiere' => 'MOCK_ISE',
            'nom_filiere' => 'Filiere ISE appliquee',
            'universite' => 'U2',
            'etablissement' => 'E2',
            'sdo_2025' => 120,
            'domaine' => 'sciences',
            'code_riasec' => 'ISE',
        ]);
        $iseF->profile()->create([
            'riasec_r'=>0.1, 'riasec_i'=>1.0, 'riasec_a'=>0.2, 'riasec_s'=>0.6, 'riasec_e'=>0.8, 'riasec_c'=>0.1,
            'gatb_g_required'=>60, 'gatb_v_required'=>60, 'gatb_n_required'=>60, 'gatb_s_required'=>60,
            'nom_filiere' => $iseF->nom_filiere,
            'domaine' => $iseF->domaine,
            'description' => 'Desc ISE',
        ]);

        $secF = Filiere::create([
            'code_filiere' => 'MOCK_SEC',
            'nom_filiere' => 'Filiere SEC sociale',
            'universite' => 'U3',
            'etablissement' => 'E3',
            'sdo_2025' => 120,
            'domaine' => 'gestion',
            'code_riasec' => 'SEC',
        ]);
        $secF->profile()->create([
            'riasec_r'=>0.1, 'riasec_i'=>0.2, 'riasec_a'=>0.2, 'riasec_s'=>0.8, 'riasec_e'=>1.0, 'riasec_c'=>0.6,
            'gatb_g_required'=>60, 'gatb_v_required'=>60, 'gatb_n_required'=>60, 'gatb_s_required'=>60,
            'nom_filiere' => $secF->nom_filiere,
            'domaine' => $secF->domaine,
            'description' => 'Desc SEC',
        ]);

        $ercF = Filiere::create([
            'code_filiere' => 'MOCK_ERC',
            'nom_filiere' => 'Filiere ERC commerciale',
            'universite' => 'U4',
            'etablissement' => 'E4',
            'sdo_2025' => 120,
            'domaine' => 'gestion',
            'code_riasec' => 'ERC',
        ]);
        $ercF->profile()->create([
            'riasec_r'=>0.6, 'riasec_i'=>0.2, 'riasec_a'=>0.2, 'riasec_s'=>0.8, 'riasec_e'=>1.0, 'riasec_c'=>0.1,
            'gatb_g_required'=>60, 'gatb_v_required'=>60, 'gatb_n_required'=>60, 'gatb_s_required'=>60,
            'nom_filiere' => $ercF->nom_filiere,
            'domaine' => $ercF->domaine,
            'description' => 'Desc ERC',
        ]);

        $results = $engine->recommend($profil, 20);

        $iasScore = null;
        $iseScore = null;
        $secScore = null;
        $ercScore = null;

        $all = array_merge(
            $results['recommandations'] ?? [],
            $results['accessibles'] ?? [],
            $results['securite'] ?? [],
            $results['ambitieuses'] ?? []
        );

        foreach ($all as $item) {
            if ($item['Code_Filiere'] === 'MOCK_IAS') $iasScore = $item['FinalScore'] ?? $item['Score_Final'];
            if ($item['Code_Filiere'] === 'MOCK_ISE') $iseScore = $item['FinalScore'] ?? $item['Score_Final'];
            if ($item['Code_Filiere'] === 'MOCK_SEC') $secScore = $item['FinalScore'] ?? $item['Score_Final'];
            if ($item['Code_Filiere'] === 'MOCK_ERC') $ercScore = $item['FinalScore'] ?? $item['Score_Final'];
        }

        $this->assertNotNull($iasScore, "IAS filiere should be scored and returned");
        $this->assertNotNull($iseScore, "ISE filiere should be scored and returned");

        // IAS vs IAS (very high) > IAS vs ISE (high)
        $this->assertGreaterThan($iseScore, $iasScore, "IAS vs IAS must rank higher than IAS vs ISE");

        if ($secScore !== null) {
            // IAS vs ISE (high) > IAS vs SEC (medium)
            $this->assertGreaterThan($secScore, $iseScore, "IAS vs ISE must rank higher than IAS vs SEC");
            if ($ercScore !== null) {
                // IAS vs SEC (medium) > IAS vs ERC (low)
                $this->assertGreaterThan($ercScore, $secScore, "IAS vs SEC must rank higher than IAS vs ERC");
            }
        }
    }
}
