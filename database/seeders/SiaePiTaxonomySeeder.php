<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Filiere;
use App\Models\FiliereProfile;
use Illuminate\Support\Str;

class SiaePiTaxonomySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Début du Seeding de la Taxonomie SIAEPI v6.0...");

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }
        DB::table('metiers')->truncate();
        DB::table('specialisations')->truncate();
        DB::table('sous_domaines')->truncate();
        DB::table('domaines')->truncate();
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // 1. Définition des Domaines
        $domaines = [
            'informatique' => [
                'nom' => 'Informatique & Technologies du Numérique',
                'description' => 'Métiers du développement logiciel, du cloud, de la cybersécurité, de la science des données et de l\'intelligence artificielle.',
                'icon' => '💻',
                'sous_domaines' => [
                    'software_engineering' => [
                        'nom' => 'Génie Logiciel & Applications',
                        'description' => 'Conception, programmation et livraison d\'applications web, mobiles et d\'architectures logicielles.',
                        'specialisations' => [
                            'web_mobile' => [
                                'nom' => 'Développement Web & Mobile',
                                'description' => 'Spécialisation dans le développement d\'applications interactives front-end et back-end.',
                                'metiers' => [
                                    [
                                        'title' => 'Développeur Fullstack Web & Mobile',
                                        'description' => 'Conçoit et déploie des applications web et mobiles modernes, du front-end au back-end.',
                                        'salary_range' => '1 200 - 2 500 TND / mois',
                                        'employability' => 'Très Élevé',
                                        'secteurs' => ['ESN (Entreprises du Numérique)', 'Startups labellisées (Startup Act)', 'Freelance'],
                                        'skills_hard' => ['PHP (Laravel)', 'JavaScript/TypeScript (React, Vue)', 'Bases de données SQL', 'Git'],
                                        'skills_soft' => ['Esprit d\'équipe', 'Résolution de problèmes', 'Autonomie'],
                                        'perspectives' => 'Évolution rapide vers Lead Developer ou CTO de startup.'
                                    ]
                                ]
                            ],
                            'software_architecture' => [
                                'nom' => 'Architecture Logiciel & DevOps',
                                'description' => 'Conception d\'architectures robustes, conteneurisation et automatisation des déploiements.',
                                'metiers' => [
                                    [
                                        'title' => 'Ingénieur Logiciel & DevOps',
                                        'description' => 'Gère l\'infrastructure d\'hébergement, automatise le déploiement continu et configure les pipelines CI/CD.',
                                        'salary_range' => '1 600 - 3 200 TND / mois',
                                        'employability' => 'Forte Croissance',
                                        'secteurs' => ['Secteur Bancaire', 'Grandes ESN', 'Multinationales'],
                                        'skills_hard' => ['Docker & Kubernetes', 'Linux & Scripting Bash', 'Services Cloud (AWS, Azure)', 'CI/CD (GitLab, Jenkins)'],
                                        'skills_soft' => ['Rigueur extrême', 'Esprit d\'analyse', 'Gestion de crise'],
                                        'perspectives' => 'Évolution vers Architecte Cloud ou VP of Engineering.'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'data_intelligence' => [
                        'nom' => 'Data Science, IA & Cybersécurité',
                        'description' => 'Valorisation de la donnée, apprentissage automatique et protection des systèmes d\'information.',
                        'specialisations' => [
                            'cybersecurity' => [
                                'nom' => 'Cybersécurité & Cloud',
                                'description' => 'Audit de sécurité, administration système sécurisée et détection de menaces.',
                                'metiers' => [
                                    [
                                        'title' => 'Expert en Cybersécurité & Systèmes',
                                        'description' => 'Sécurise les SI, effectue des audits de vulnérabilité et prévient les intrusions réseau.',
                                        'salary_range' => '1 800 - 3 500 TND / mois',
                                        'employability' => 'Forte Croissance',
                                        'secteurs' => ['Banques et Assurances', 'Opérateurs Télécom', 'Ministères et Sécurité Nationale'],
                                        'skills_hard' => ['Sécurité Réseau', 'Normes ISO 27001', 'Cryptographie', 'Linux Administration'],
                                        'skills_soft' => ['Discrétion absolue', 'Gestion du stress', 'Réactivité'],
                                        'perspectives' => 'Devenir Responsable de la Sécurité des Systèmes d\'Information (RSSI).'
                                    ]
                                ]
                            ],
                            'data_ia' => [
                                'nom' => 'Intelligence Artificielle & Sciences des Données',
                                'description' => 'Création de modèles prédictifs, traitement du langage naturel et vision industrielle.',
                                'metiers' => [
                                    [
                                        'title' => 'Ingénieur IA & Data Scientist',
                                        'description' => 'Conçoit des algorithmes de Machine Learning pour valoriser les données métiers des entreprises.',
                                        'salary_range' => '1 700 - 3 000 TND / mois',
                                        'employability' => 'Très Élevé',
                                        'secteurs' => ['Sociétés de Conseil', 'Centres de R&D', 'Startups technologiques'],
                                        'skills_hard' => ['Python (Scikit-Learn, TensorFlow)', 'SQL / NoSQL', 'Statistiques et Modélisation', 'Big Data (Spark)'],
                                        'skills_soft' => ['Rigueur scientifique', 'Esprit critique', 'Bonne communication'],
                                        'perspectives' => 'Devenir Chief Data Officer ou Chercheur Appliqué.'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'sante' => [
                'nom' => 'Santé & Paramédical',
                'description' => 'Métiers de la médecine humaine, de la pharmacie et des soins d\'assistance paramédicale.',
                'icon' => '🏥',
                'sous_domaines' => [
                    'medical' => [
                        'nom' => 'Médecine & Pharmacie',
                        'description' => 'Activité clinique de diagnostic et sciences de la formulation pharmaceutique.',
                        'specialisations' => [
                            'medecine' => [
                                'nom' => 'Médecine Générale & Spécialisée',
                                'description' => 'Cursus médical pour devenir praticien en milieu hospitalier ou libéral.',
                                'metiers' => [
                                    [
                                        'title' => 'Médecin (Généraliste ou Spécialiste)',
                                        'description' => 'Assure le diagnostic, prescrit les médicaments et coordonne le suivi de santé des patients.',
                                        'salary_range' => '1 800 - 4 000 TND / mois',
                                        'employability' => 'Très Élevé',
                                        'secteurs' => ['Hôpitaux Publics', 'Cliniques Privées', 'Cabinets Libéraux'],
                                        'skills_hard' => ['Diagnostic Clinique', 'Pharmacologie', 'Techniques Médicales de Spécialité'],
                                        'skills_soft' => ['Empathie', 'Résistance physique et mentale', 'Éthique professionnelle'],
                                        'perspectives' => 'Évolution vers Chef de Clinique ou Chef de Service Hospitalier.'
                                    ]
                                ]
                            ],
                            'pharmacie' => [
                                'nom' => 'Sciences Pharmaceutiques',
                                'description' => 'Production, contrôle et dispensation des produits thérapeutiques.',
                                'metiers' => [
                                    [
                                        'title' => 'Pharmacien (Officine ou Laboratoire)',
                                        'description' => 'Délivre les médicaments et conseille les patients, ou formule les produits en industrie.',
                                        'salary_range' => '1 500 - 2 800 TND / mois',
                                        'employability' => 'Élevé',
                                        'secteurs' => ['Officines Privées', 'Laboratoires Pharmaceutiques', 'Grossistes répartiteurs'],
                                        'skills_hard' => ['Chimie Organique', 'Réglementation Médicale', 'Gestion d\'Officine'],
                                        'skills_soft' => ['Rigueur', 'Sens du contact humain', 'Précision'],
                                        'perspectives' => 'Devenir propriétaire d\'une officine ou Directeur Industriel de production.'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'paramedical' => [
                        'nom' => 'Paramédical & Rééducation',
                        'description' => 'Soins et techniques de réadaptation physique et d\'assistance médicale.',
                        'specialisations' => [
                            'reeducation' => [
                                'nom' => 'Kinésithérapie & Rééducation',
                                'description' => 'Soins manuels et techniques physiques pour restaurer la mobilité corporelle.',
                                'metiers' => [
                                    [
                                        'title' => 'Kinésithérapeute',
                                        'description' => 'Rééduque les capacités motrices et articulaires des patients souffrant de traumatismes.',
                                        'salary_range' => '900 - 1 800 TND / mois',
                                        'employability' => 'Modéré',
                                        'secteurs' => ['Centres de Thalassothérapie', 'Cabinets Libéraux', 'Cliniques'],
                                        'skills_hard' => ['Anatomie & Biomécanique', 'Techniques de massage thérapeutique', 'Rééducation fonctionnelle'],
                                        'skills_soft' => ['Empathie', 'Sens du contact physique', 'Patience'],
                                        'perspectives' => 'Devenir gérant de cabinet ou s\'orienter vers le sport professionnel.'
                                    ]
                                ]
                            ],
                            'soins_infirmiers' => [
                                'nom' => 'Soins Infirmiers & Sage-Femme',
                                'description' => 'Surveillance de l\'état de santé et administration des soins prescrits.',
                                'metiers' => [
                                    [
                                        'title' => 'Infirmier de Soins Généraux',
                                        'description' => 'Dispense les soins requis et assure le bien-être physique et psychologique du patient hospitalisé.',
                                        'salary_range' => '800 - 1 500 TND / mois',
                                        'employability' => 'Très Élevé',
                                        'secteurs' => ['Hôpitaux Publics', 'Cliniques Privées', 'Cabinets de Soins'],
                                        'skills_hard' => ['Gestes de premiers secours', 'Administration de traitements', 'Hygiène hospitalière'],
                                        'skills_soft' => ['Vigilance', 'Dévouement', 'Gestion des émotions'],
                                        'perspectives' => 'Évolution vers Infirmier Anesthésiste, Major de service ou Cadre de santé.'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'technique' => [
                'nom' => 'Ingénierie & Sciences Techniques',
                'description' => 'Conception industrielle, construction civile, automatisme, énergétique et fabrication.',
                'icon' => '⚙️',
                'sous_domaines' => [
                    'industrial_engineering' => [
                        'nom' => 'Génie Industriel & Maintenance',
                        'description' => 'Optimisation des outils industriels, mécatronique et électromécanique.',
                        'specialisations' => [
                            'electromecanique' => [
                                'nom' => 'Électromécanique & Maintenance',
                                'description' => 'Diagnostic et maintien opérationnel des systèmes électriques et mécaniques industriels.',
                                'metiers' => [
                                    [
                                        'title' => 'Ingénieur en Maintenance Industrielle',
                                        'description' => 'Planifie les opérations de maintenance préventive et corrective sur les chaînes de production.',
                                        'salary_range' => '1 300 - 2 200 TND / mois',
                                        'employability' => 'Élevé',
                                        'secteurs' => ['Usines Manufacturières', 'Compagnies Énergétiques (STEG)', 'Cimenteries'],
                                        'skills_hard' => ['Automates programmables', 'Électricité industrielle', 'GMAO', 'Dessin Industriel'],
                                        'skills_soft' => ['Réactivité', 'Gestion d\'équipe', 'Méthode'],
                                        'perspectives' => 'Devenir Responsable Maintenance de site ou Directeur Technique.'
                                    ]
                                ]
                            ],
                            'mecatronique' => [
                                'nom' => 'Mécatronique & Automatisme',
                                'description' => 'Intégration synergique de la mécanique, de l\'électronique et de l\'informatique industrielle.',
                                'metiers' => [
                                    [
                                        'title' => 'Ingénieur en Automatisation & Robotique',
                                        'description' => 'Conçoit et programme des systèmes automatiques complexes et des bras robotisés.',
                                        'salary_range' => '1 400 - 2 500 TND / mois',
                                        'employability' => 'Élevé',
                                        'secteurs' => ['Industrie Automobile', 'Aéronautique', 'Éditeurs de solutions industrielles'],
                                        'skills_hard' => ['Programmation C/C++', 'Microcontrôleurs', 'Conception Assistée par Ordinateur (CAO)', 'Électronique de puissance'],
                                        'skills_soft' => ['Créativité technique', 'Sens de la logique', 'Curiosité'],
                                        'perspectives' => 'Évolution vers Chef de Projet Innovation ou Consultant Industriel.'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'civil_engineering' => [
                        'nom' => 'Génie Civil & Bâtiment',
                        'description' => 'Conception d\'infrastructures publiques, routes et bâtiments résidentiels.',
                        'specialisations' => [
                            'batiment' => [
                                'nom' => 'Bâtiment & Travaux Publics',
                                'description' => 'Pilotage de chantiers, dimensionnement et calculs de résistance des structures.',
                                'metiers' => [
                                    [
                                        'title' => 'Ingénieur Génie Civil & Conducteur de Travaux',
                                        'description' => 'Supervise les travaux de construction, vérifie le respect des normes de sécurité et la qualité des matériaux.',
                                        'salary_range' => '1 200 - 2 000 TND / mois',
                                        'employability' => 'Modéré',
                                        'secteurs' => ['Entreprises de BTP', 'Bureaux d\'Études structures', 'Municipalités'],
                                        'skills_hard' => ['Calcul de structures (Robot, AutoCAD)', 'Planification de chantier (MS Project)', 'Topographie de base'],
                                        'skills_soft' => ['Autorité naturelle', 'Sens des responsabilités', 'Négociation'],
                                        'perspectives' => 'Devenir Directeur de Projets BTP ou fonder son propre bureau d\'études.'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'sciences' => [
                'nom' => 'Sciences Fondamentales & de la Matière',
                'description' => 'Analyses scientifiques, physique des matériaux, chimie et modélisation mathématique.',
                'icon' => '🔬',
                'sous_domaines' => [
                    'fundamental_sciences' => [
                        'nom' => 'Laboratoire, Physique & Chimie',
                        'description' => 'Méthodes d\'analyses quantitatives et expérimentales en milieu clos.',
                        'specialisations' => [
                            'analyses' => [
                                'nom' => 'Analyses de Laboratoire & Qualité',
                                'description' => 'Réalisation de manipulations microbiologiques, cliniques ou agroalimentaires.',
                                'metiers' => [
                                    [
                                        'title' => 'Technicien de Laboratoire ou Responsable Qualité',
                                        'description' => 'Effectue des prélèvements et des analyses biochimiques pour valider la conformité ou le diagnostic.',
                                        'salary_range' => '800 - 1 400 TND / mois',
                                        'employability' => 'Modéré',
                                        'secteurs' => ['Laboratoires privés d\'analyses', 'Hôpitaux', 'Industries Agroalimentaires'],
                                        'skills_hard' => ['Microbiologie clinique', 'Normes ISO 9001 / ISO 22000', 'Chromatographie (HPLC)', 'Hygiène (HACCP)'],
                                        'skills_soft' => ['Rigueur absolue', 'Précision', 'Esprit d\'observation'],
                                        'perspectives' => 'Devenir Auditeur Qualité Senior ou Directeur de Laboratoire.'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'applied_mathematics' => [
                        'nom' => 'Mathématiques Appliquées',
                        'description' => 'Traitement de données probabilistes, calcul actuariel et modélisation.',
                        'specialisations' => [
                            'statistics' => [
                                'nom' => 'Statistiques & Modélisation',
                                'description' => 'Application des théories mathématiques à la gestion des risques et aux prévisions économiques.',
                                'metiers' => [
                                    [
                                        'title' => 'Actuaire & Data Analyst Quantitatif',
                                        'description' => 'Modélise les risques assurantiels et financiers à l\'aide d\'outils stochastiques.',
                                        'salary_range' => '1 400 - 2 600 TND / mois',
                                        'employability' => 'Élevé',
                                        'secteurs' => ['Compagnies d\'assurances', 'Banques de Financement', 'Cabinets de Conseil en Risques'],
                                        'skills_hard' => ['Statistiques et Probabilités', 'R / Python', 'SQL et requêtage', 'Mathématiques financières'],
                                        'skills_soft' => ['Esprit critique', 'Rigueur intellectuelle', 'Synthèse'],
                                        'perspectives' => 'Devenir Risk Manager ou Directeur Actuariat.'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'economie' => [
                'nom' => 'Gestion, Économie & Commerce',
                'description' => 'Gestion des ressources financières, comptables, marketing et humaines des organisations.',
                'icon' => '📊',
                'sous_domaines' => [
                    'finance_accounting' => [
                        'nom' => 'Comptabilité, Audit & Finance',
                        'description' => 'Administration des flux comptables, audit légal et ingénierie de financement.',
                        'specialisations' => [
                            'audit_comptabilite' => [
                                'nom' => 'Audit & Comptabilité',
                                'description' => 'Gestion des livres comptables, élaboration des liasses fiscales et audit externe.',
                                'metiers' => [
                                    [
                                        'title' => 'Auditeur Financier ou Expert-Comptable Stagiaire',
                                        'description' => 'Examine la régularité et la sincérité des comptes des sociétés tunisiennes.',
                                        'salary_range' => '1 000 - 2 000 TND / mois',
                                        'employability' => 'Élevé',
                                        'secteurs' => ['Cabinets d\'Expertise Comptable (Big 4)', 'Directions Financières', 'Administrations Fiscales'],
                                        'skills_hard' => ['Comptabilité tunisienne', 'Droit des affaires et fiscalité', 'Techniques d\'audit', 'Excel Avancé'],
                                        'skills_soft' => ['Rigueur mathématique', 'Sens éthique et déontologie', 'Méthode'],
                                        'perspectives' => 'Devenir Directeur Administratif et Financier (DAF) ou Expert-Comptable inscrit à l\'OECT.'
                                    ]
                                ]
                            ],
                            'finance' => [
                                'nom' => 'Finance d\'Entreprise & Banque',
                                'description' => 'Analyse des risques d\'investissement et distribution de services bancaires.',
                                'metiers' => [
                                    [
                                        'title' => 'Chargé d\'Affaires Banque ou Analyste Crédit',
                                        'description' => 'Analyse les bilans des entreprises et leur accorde des lignes de crédit de fonctionnement.',
                                        'salary_range' => '1 200 - 2 200 TND / mois',
                                        'employability' => 'Stable',
                                        'secteurs' => ['Banques de Réseau (BIAT, Attijari)', 'Établissements de Leasing', 'Compagnies d\'Assurances'],
                                        'skills_hard' => ['Analyse Financière de bilan', 'Évaluation de projets', 'Réglementation Banque Centrale (BCT)'],
                                        'skills_soft' => ['Aisance relationnelle', 'Persuasion commerciale', 'Esprit d\'analyse'],
                                        'perspectives' => 'Devenir Directeur d\'Agence Bancaire ou Gestionnaire de Fonds.'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'management_marketing' => [
                        'nom' => 'Management, Marketing & Commerce',
                        'description' => 'Définition des stratégies de prix, distribution, communication et ressources humaines.',
                        'specialisations' => [
                            'marketing' => [
                                'nom' => 'Marketing, Vente & Web-marketing',
                                'description' => 'Promotion des ventes, fidélisation client et optimisation de la visibilité sur les plateformes numériques.',
                                'metiers' => [
                                    [
                                        'title' => 'Chef de Produit ou Responsable Marketing Digital',
                                        'description' => 'Conçoit et suit les gammes de produits ou déploie les stratégies d\'acquisition digitale.',
                                        'salary_range' => '1 000 - 1 800 TND / mois',
                                        'employability' => 'Très Élevé',
                                        'secteurs' => ['Agences de Communication', 'Entreprises de Grande Distribution', 'Opérateurs Télécom'],
                                        'skills_hard' => ['SEO/SEA', 'Réseaux Sociaux et CRM', 'Stratégie de tarification', 'Copywriting'],
                                        'skills_soft' => ['Créativité', 'Aisance rédactionnelle', 'Esprit d\'écoute'],
                                        'perspectives' => 'Évolution vers Directeur Marketing ou Brand Manager.'
                                    ]
                                ]
                            ],
                            'economie_gestion' => [
                                'nom' => 'Sciences Économiques & Gestion',
                                'description' => 'Analyse économique, études de marché, gestion d\'entreprise et statistiques d\'aide à la décision.',
                                'metiers' => [
                                    [
                                        'title' => 'Économiste & Chargé d\'études statistiques',
                                        'description' => 'Réalise des analyses conjoncturelles, des études de marché et des prévisions de vente pour guider les choix stratégiques.',
                                        'salary_range' => '1 100 - 2 000 TND / mois',
                                        'employability' => 'Élevé',
                                        'secteurs' => ['Bureaux d\'études', 'Directions de la Planification', 'Grandes Entreprises'],
                                        'skills_hard' => ['Analyse statistique', 'Traitement de données (SPSS, R)', 'Macroéconomie', 'Modélisation financière'],
                                        'skills_soft' => ['Esprit d\'analyse', 'Rigueur', 'Qualités de synthèse'],
                                        'perspectives' => 'Évolution vers Chef du département planification ou Consultant senior.'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'lettres' => [
                'nom' => 'Langues, Lettres & Sciences Humaines',
                'description' => 'Étude des littératures, traduction, interprétariat et pédagogie des langues.',
                'icon' => '📖',
                'sous_domaines' => [
                    'translation_languages' => [
                        'nom' => 'Langues & Traduction',
                        'description' => 'Régles grammaticales comparatives et techniques de transposition sémantique.',
                        'specialisations' => [
                            'translation' => [
                                'nom' => 'Traduction & Rédaction Bilingue',
                                'description' => 'Interprétation simultanée ou traduction de documents administratifs et techniques.',
                                'metiers' => [
                                    [
                                        'title' => 'Traducteur ou Rédacteur SEO Bilingue',
                                        'description' => 'Traduit les contrats, guides techniques et supports de communication pour des clients internationaux.',
                                        'salary_range' => '800 - 1 600 TND / mois',
                                        'employability' => 'Modéré',
                                        'secteurs' => ['Bureaux de Traduction assermentés', 'Agences de contenu web', 'Télétravail en Freelance'],
                                        'skills_hard' => ['Parfaite maîtrise de 3 langues', 'Rédaction optimisée SEO', 'Outils de TAO (SDL Trados)'],
                                        'skills_soft' => ['Sens esthétique du texte', 'Rigueur linguistique', 'Respect des délais'],
                                        'perspectives' => 'Devenir Traducteur Assermenté agréé par l\'État ou Consultant éditorial.'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'humanities' => [
                        'nom' => 'Lettres & Sciences Humaines',
                        'description' => 'Histoire, philosophie et étude des structures grammaticales et pédagogiques.',
                        'specialisations' => [
                            'lettres_enseignements' => [
                                'nom' => 'Lettres & Langues de l\'Enseignement',
                                'description' => 'Préparation aux concours de recrutement pour enseigner les langues littéraires.',
                                'metiers' => [
                                    [
                                        'title' => 'Enseignant de Langues (Primaire ou Secondaire)',
                                        'description' => 'Transmet les savoirs littéraires et assure le développement pédagogique des élèves.',
                                        'salary_range' => '800 - 1 400 TND / mois',
                                        'employability' => 'Stable',
                                        'secteurs' => ['Établissements scolaires privés', 'Écoles de langues', 'Ministère de l\'Éducation'],
                                        'skills_hard' => ['Pédagogie et didactique', 'Planification de cours', 'Évaluation des apprentissages'],
                                        'skills_soft' => ['Patience', 'Clarté de parole', 'Autorité bienveillante'],
                                        'perspectives' => 'Évolution vers Inspecteur de l\'Éducation ou Directeur d\'école.'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'social' => [
                'nom' => 'Droit, Sciences Sociales & Éducation',
                'description' => 'Sciences juridiques, assistance sociale, formation professionnelle et éducation physique.',
                'icon' => '⚖️',
                'sous_domaines' => [
                    'law_sciences' => [
                        'nom' => 'Droit & Sciences Juridiques',
                        'description' => 'Défense en justice, conseils contractuels et règles du droit civil et public.',
                        'specialisations' => [
                            'droit' => [
                                'nom' => 'Droit des Affaires & Droit Privé',
                                'description' => 'Spécialisation dans les litiges commerciaux, contrats d\'affaires et arbitrage.',
                                'metiers' => [
                                    [
                                        'title' => 'Avocat ou Collaborateur Juridique',
                                        'description' => 'Assure le conseil juridique et la plaidoirie devant les tribunaux d\'instance.',
                                        'salary_range' => '850 - 2 000 TND / mois',
                                        'employability' => 'Stable',
                                        'secteurs' => ['Cabinets d\'Avocats', 'Directions Juridiques d\'entreprises', 'Notariats'],
                                        'skills_hard' => ['Droit Commercial tunisien', 'Rédaction d\'actes juridiques', 'Techniques de Plaidoirie'],
                                        'skills_soft' => ['Aisance oratoire', 'Combativité', 'Intégrité morale'],
                                        'perspectives' => 'Avocat à la Cour de Cassation ou Arbitre Commercial agréé.'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'social_education' => [
                        'nom' => 'Sciences Sociales & Éducation',
                        'description' => 'Accompagnement social, médiation scolaire et formation aux sports.',
                        'specialisations' => [
                            'education' => [
                                'nom' => 'Enseignement & Pédagogie',
                                'description' => 'Préparation aux fonctions de conseiller éducatif ou d\'enseignant de sport.',
                                'metiers' => [
                                    [
                                        'title' => 'Conseiller d\'Éducation ou Formateur Sportif',
                                        'description' => 'Encadre et oriente les élèves, ou élabore des programmes d\'entraînement physique.',
                                        'salary_range' => '850 - 1 400 TND / mois',
                                        'employability' => 'Stable',
                                        'secteurs' => ['Centres de Jeunesse', 'Clubs de Sport', 'Lycées et Collèges'],
                                        'skills_hard' => ['Physiologie de l\'effort', 'Conduite d\'activités collectives', 'Psychologie de l\'enfant'],
                                        'skills_soft' => ['Sens de l\'animation', 'Patience', 'Empathie'],
                                        'perspectives' => 'Devenir Coordinateur Régional des Sports ou Directeur de centre de vacances.'
                                    ]
                                ]
                            ],
                            'psychologie' => [
                                'nom' => 'Psychologie & Accompagnement Social',
                                'description' => 'Entretiens cliniques, psychométrie et prise en charge des publics vulnérables.',
                                'metiers' => [
                                    [
                                        'title' => 'Psychologue clinicien ou Conseiller d\'Orientation',
                                        'description' => 'Anime des consultations d\'orientation scolaire et d\'aide psycho-sociale auprès des étudiants.',
                                        'salary_range' => '900 - 1 600 TND / mois',
                                        'employability' => 'Stable',
                                        'secteurs' => ['Cabinets de consultation', 'ANETI (Emploi)', 'Écoles et Universités'],
                                        'skills_hard' => ['Techniques d\'entretiens cliniques', 'Analyse psychologique', 'Outils de diagnostic mental'],
                                        'skills_soft' => ['Écoute active absolue', 'Empathie profonde', 'Neutralité'],
                                        'perspectives' => 'Fonder un cabinet libéral ou devenir consultant en ressources humaines.'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'arts' => [
                'nom' => 'Arts, Design & Architecture',
                'description' => 'Création graphique, arts plastiques, musique, architecture d\'intérieur et journalisme.',
                'icon' => '🎨',
                'sous_domaines' => [
                    'design_arts' => [
                        'nom' => 'Design & Création Visuelle',
                        'description' => 'Création d\'interfaces visuelles, modélisation 2D/3D et stratégies de marque.',
                        'specialisations' => [
                            'design_uiux' => [
                                'nom' => 'Design Graphique & UI-UX',
                                'description' => 'Modélisation des expériences utilisateurs et ergonomie des interfaces applicatives.',
                                'metiers' => [
                                    [
                                        'title' => 'Designer UI-UX / Graphiste numérique',
                                        'description' => 'Conçoit la charte graphique et l\'architecture fonctionnelle des produits digitaux.',
                                        'salary_range' => '1 000 - 2 000 TND / mois',
                                        'employability' => 'Forte Croissance',
                                        'secteurs' => ['Agences de communication', 'Startups technologiques', 'Freelance'],
                                        'skills_hard' => ['Figma / Adobe XD', 'Photoshop & Illustrator', 'Principes d\'ergonomie web', 'Responsive Design'],
                                        'skills_soft' => ['Créativité', 'Sensibilité artistique', 'Esprit critique'],
                                        'perspectives' => 'Évolution vers Directeur Artistique ou Product Designer.'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'architecture_arts' => [
                        'nom' => 'Architecture & Espaces',
                        'description' => 'Conception d\'espaces fonctionnels intérieurs et extérieurs.',
                        'specialisations' => [
                            'interior_archi' => [
                                'nom' => 'Architecture d\'Intérieur & Design d\'Espace',
                                'description' => 'Agencement d\'intérieurs résidentiels ou commerciaux alliant esthétique et conformité constructive.',
                                'metiers' => [
                                    [
                                        'title' => 'Architecte d\'Intérieur',
                                        'description' => 'Conçoit des projets d\'agencement et de rénovation pour des maisons et bureaux.',
                                        'salary_range' => '1 100 - 1 800 TND / mois',
                                        'employability' => 'Stable',
                                        'secteurs' => ['Bureaux d\'architectes', 'Showrooms', 'Activité indépendante'],
                                        'skills_hard' => ['SketchUp & AutoCAD 3D', 'Estimation de devis de travaux', 'Sélection et harmonie des matières'],
                                        'skills_soft' => ['Sens esthétique développé', 'Organisation', 'Sens du contact client'],
                                        'perspectives' => 'Devenir gérant d\'agence indépendante ou Designer d\'espace de marque.'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // 2. Écrire dans les tables
        foreach ($domaines as $dCode => $dData) {
            $domaineId = DB::table('domaines')->insertGetId([
                'code' => $dCode,
                'nom' => $dData['nom'],
                'description' => $dData['description'],
                'icon' => $dData['icon'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($dData['sous_domaines'] as $sdCode => $sdData) {
                $sousDomaineId = DB::table('sous_domaines')->insertGetId([
                    'domaine_id' => $domaineId,
                    'code' => $sdCode,
                    'nom' => $sdData['nom'],
                    'description' => $sdData['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($sdData['specialisations'] as $specCode => $specData) {
                    $specialisationId = DB::table('specialisations')->insertGetId([
                        'sous_domaine_id' => $sousDomaineId,
                        'code' => $specCode,
                        'nom' => $specData['nom'],
                        'description' => $specData['description'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    foreach ($specData['metiers'] as $metier) {
                        DB::table('metiers')->insert([
                            'specialisation_id' => $specialisationId,
                            'title' => $metier['title'],
                            'description' => $metier['description'],
                            'salary_range' => $metier['salary_range'],
                            'employability' => $metier['employability'],
                            'secteurs' => json_encode($metier['secteurs']),
                            'skills_hard' => json_encode($metier['skills_hard']),
                            'skills_soft' => json_encode($metier['skills_soft']),
                            'perspectives' => $metier['perspectives'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        $this->command->info("Structure de taxonomie insérée avec succès.");

        // 3. Classification dynamique des filières existantes
        $this->command->info("Début de la classification automatique des 2241 filières...");

        $filieres = Filiere::all();
        $dbDomaines = DB::table('domaines')->get()->keyBy('code');
        $dbSousDomaines = DB::table('sous_domaines')->get()->keyBy('code');
        $dbSpecialisations = DB::table('specialisations')->get()->keyBy('code');

        $count = 0;
        $filiereGroups = [];
        $profileGroups = [];

        foreach ($filieres as $filiere) {
            $nom = mb_strtolower($filiere->nom_filiere);
            $excelDom = $filiere->domaine;

            // Détection du Domaine
            $dCode = 'social'; // Fallback par défaut

            if (preg_match('/inform|algorithme|réseau|systèm|logiciel|cyber|data|développ|télécom|comput/', $nom)) {
                $dCode = 'informatique';
            } elseif (preg_match('/médic|santé|pharmac|infirmier|kiné|dentair|obstétri|sage-femme|anesthé/', $nom)) {
                $dCode = 'sante';
            } elseif (preg_match('/gestion|comptab|finance|audit|marketing|commerce|management|administration|affaires|économ/', $nom)) {
                $dCode = 'economie';
            } elseif (preg_match('/génie|mécanique|électrique|civil|industri|maintenance|robotique|automatique|bâtiment|énergét/', $nom)) {
                $dCode = 'technique';
            } elseif (preg_match('/biologie|chimie|physique|géologie|sciences de la vie|math|statistique/', $nom)) {
                $dCode = 'sciences';
            } elseif (preg_match('/arabe|français|anglais|italien|espagnol|traduction|langues|allemand|russe|chinois|lettres/', $nom)) {
                $dCode = 'lettres';
            } elseif (preg_match('/art|design|musique|architecture|cinéma|journalisme|communication/', $nom)) {
                $dCode = 'arts';
            } else {
                // Fallback basé sur la catégorie de l'Excel d'origine
                $excelDomNorm = mb_strtolower($excelDom);
                if (str_contains($excelDomNorm, 'informatique')) $dCode = 'informatique';
                elseif (str_contains($excelDomNorm, 'technique') || str_contains($excelDomNorm, 'technologie')) $dCode = 'technique';
                elseif (str_contains($excelDomNorm, 'économie') || str_contains($excelDomNorm, 'gestion')) $dCode = 'economie';
                elseif (str_contains($excelDomNorm, 'lettres')) $dCode = 'lettres';
                elseif (str_contains($excelDomNorm, 'expérimentales')) $dCode = 'sciences';
                elseif (str_contains($excelDomNorm, 'sport')) $dCode = 'social';
            }

            // Détection du Sous-domaine & Spécialisation
            $specCode = null;

            switch ($dCode) {
                case 'informatique':
                    if (preg_match('/cyber|sécurité|cloud/', $nom)) {
                        $specCode = 'cybersecurity';
                    } elseif (preg_match('/data|données|intelligence artificielle|ia|décision/', $nom)) {
                        $specCode = 'data_ia';
                    } elseif (preg_match('/architecture|devops|système|réseau|télécom|embarqué/', $nom)) {
                        $specCode = 'software_architecture';
                    } else {
                        $specCode = 'web_mobile';
                    }
                    break;

                case 'sante':
                    if (preg_match('/infirmier|sage-femme|obstétri/', $nom)) {
                        $specCode = 'soins_infirmiers';
                    } elseif (preg_match('/kiné|rééduc|physio/', $nom)) {
                        $specCode = 'reeducation';
                    } elseif (preg_match('/pharmac/', $nom)) {
                        $specCode = 'pharmacie';
                    } else {
                        $specCode = 'medecine';
                    }
                    break;

                case 'technique':
                    if (preg_match('/civil|bâtiment|btp|infrastr/', $nom)) {
                        $specCode = 'batiment';
                    } elseif (preg_match('/mécatron|robot|automati|électron/', $nom)) {
                        $specCode = 'mecatronique';
                    } else {
                        $specCode = 'electromecanique';
                    }
                    break;

                case 'sciences':
                    if (preg_match('/math|stat|actu/', $nom)) {
                        $specCode = 'statistics';
                    } else {
                        $specCode = 'analyses';
                    }
                    break;

                case 'economie':
                    if (preg_match('/audit|comptab|fiscal|expert/', $nom)) {
                        $specCode = 'audit_comptabilite';
                    } elseif (preg_match('/finance|banque|assur|monét/', $nom)) {
                        $specCode = 'finance';
                    } elseif (preg_match('/économ|gestion|management|administration/', $nom)) {
                        $specCode = 'economie_gestion';
                    } else {
                        $specCode = 'marketing';
                    }
                    break;

                case 'lettres':
                    if (preg_match('/trad|interpr|biling|rédac/', $nom)) {
                        $specCode = 'translation';
                    } else {
                        $specCode = 'lettres_enseignements';
                    }
                    break;

                case 'social':
                    if (preg_match('/droit|jurid|notar|avocat/', $nom)) {
                        $specCode = 'droit';
                    } elseif (preg_match('/psycho|socio|social/', $nom)) {
                        $specCode = 'psychologie';
                    } else {
                        $specCode = 'education';
                    }
                    break;

                case 'arts':
                    if (preg_match('/archi|intér|espace|paysa/', $nom)) {
                        $specCode = 'interior_archi';
                    } else {
                        $specCode = 'design_uiux';
                    }
                    break;
            }

            // Group for Filiere updates
            $groupKey = "{$dCode}|{$specCode}";
            if (!isset($filiereGroups[$groupKey])) {
                $filiereGroups[$groupKey] = [
                    'dCode' => $dCode,
                    'specCode' => $specCode,
                    'codes' => []
                ];
            }
            $filiereGroups[$groupKey]['codes'][] = $filiere->code_filiere;

            // Group for FiliereProfile updates
            if (!isset($profileGroups[$dCode])) {
                $profileGroups[$dCode] = [];
            }
            $profileGroups[$dCode][] = $filiere->code_filiere;

            $count++;
        }

        DB::transaction(function () use ($filiereGroups, $profileGroups, $dbDomaines, $dbSpecialisations) {
            // 1. Bulk update filieres
            foreach ($filiereGroups as $group) {
                $dCode = $group['dCode'];
                $specCode = $group['specCode'];
                $codes = $group['codes'];

                $dId = $dbDomaines[$dCode]->id ?? null;
                $specObj = $dbSpecialisations[$specCode] ?? null;
                $specId = $specObj?->id ?? null;
                $sdId = $specObj?->sous_domaine_id ?? null;

                Filiere::whereIn('code_filiere', $codes)->update([
                    'domaine_id' => $dId,
                    'sous_domaine_id' => $sdId,
                    'specialisation_id' => $specId,
                ]);
            }

            // 2. Bulk update profiles (excluding individual SDO adjustment)
            foreach ($profileGroups as $dCode => $codes) {
                $employabilityRate = match($dCode) {
                    'informatique' => 88.5,
                    'sante' => 92.0,
                    'technique' => 78.4,
                    'economie' => 70.2,
                    'sciences' => 58.6,
                    'social' => 61.2,
                    'lettres' => 45.4,
                    'arts' => 68.0,
                    default => 60.0
                };

                $growthRate = match($dCode) {
                    'informatique' => 5.4,
                    'sante' => 3.8,
                    'technique' => 2.9,
                    'economie' => 1.5,
                    'sciences' => 0.8,
                    'social' => 1.1,
                    'lettres' => -0.5,
                    'arts' => 2.4,
                    default => 1.0
                };

                $annualOpenings = match($dCode) {
                    'informatique' => 1200,
                    'sante' => 800,
                    'technique' => 950,
                    'economie' => 1100,
                    'sciences' => 400,
                    'social' => 750,
                    'lettres' => 300,
                    'arts' => 500,
                    default => 500
                };

                FiliereProfile::whereIn('code_filiere', $codes)->update([
                    'employability_rate' => $employabilityRate,
                    'growth_rate' => $growthRate,
                    'annual_openings' => $annualOpenings,
                    'domaine' => $dCode
                ]);
            }

            // 3. Excellent SDO (> 140) adjustment (single raw query)
            $excellentCodes = Filiere::where('sdo_2023', '>', 140)
                ->orWhere('sdo_2024', '>', 140)
                ->orWhere('sdo_2025', '>', 140)
                ->pluck('code_filiere')
                ->toArray();

            if (!empty($excellentCodes)) {
                FiliereProfile::whereIn('code_filiere', $excellentCodes)
                    ->whereNotNull('employability_rate')
                    ->update([
                        'employability_rate' => DB::raw('LEAST(99.0, employability_rate + 5)')
                    ]);
            }
        });

        $this->command->info("Classification terminée ! {$count} filières ont été structurées et enrichies.");
    }
}
