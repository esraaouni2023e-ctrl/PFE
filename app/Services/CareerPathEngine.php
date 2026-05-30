<?php

namespace App\Services;

class CareerPathEngine
{
    /**
     * Retourne la fiche métier et les débouchés pour un domaine spécifique.
     */
    public function getCareersForDomain(string $domain): array
    {
        $domain = strtolower(trim($domain));

        $data = [
            'informatique' => [
                'domain_label' => 'Informatique & Technologies du Numérique',
                'careers' => [
                    [
                        'title' => 'Ingénieur Logiciel & Développeur Fullstack',
                        'description' => 'Conçoit, développe et maintient des applications web et mobiles modernes de bout en bout.',
                        'secteurs' => ['Technopole El Ghazala', 'Espaces Co-working de Tunis', 'Sociétés Off-shore (ESN)', 'Startups labellisées (Startup Act)'],
                        'skills_hard' => ['PHP (Laravel)', 'Javascript/TypeScript (React, Vue)', 'Bases de données SQL & NoSQL', 'API REST & Git'],
                        'skills_soft' => ['Esprit d\'analyse', 'Résolution de problèmes complexes', 'Autonomie', 'Travail en équipe'],
                        'salary_range' => '1 200 - 2 200 TND / mois',
                        'employability' => 'Très Élevé',
                    ],
                    [
                        'title' => 'Ingénieur Cloud & DevOps / Administrateur Système',
                        'description' => 'Gère l\'infrastructure d\'hébergement, automatise le déploiement continu et assure la sécurité des services cloud.',
                        'secteurs' => ['Opérateurs Télécom (Tunisie Telecom, Ooredoo, Orange)', 'Banques et Assurances', 'Hébergeurs locaux et ESN'],
                        'skills_hard' => ['Linux & Scripting', 'Docker & Kubernetes', 'Services Cloud (AWS, Azure)', 'CI/CD (GitLab, Jenkins)'],
                        'skills_soft' => ['Rigueur extrême', 'Gestion du stress', 'Résilience', 'Communication inter-équipes'],
                        'salary_range' => '1 500 - 2 800 TND / mois',
                        'employability' => 'Forte Croissance',
                    ],
                    [
                        'title' => 'Analyste Data & Ingénieur Business Intelligence',
                        'description' => 'Traite, modélise et analyse de grands volumes de données pour aider les entreprises tunisiennes et internationales à prendre des décisions stratégiques.',
                        'secteurs' => ['Cabinets de conseil', 'Multinationales (Tunis Lac, Charguia)', 'Secteur bancaire tunisien', 'Grands groupes de distribution'],
                        'skills_hard' => ['Python / R', 'SQL & Modélisation Data', 'Outils de BI (PowerBI, Tableau)', 'Statistiques descriptives'],
                        'skills_soft' => ['Esprit critique', 'Excellente communication orale et écrite', 'Synthèse', 'Vulgarisation technique'],
                        'salary_range' => '1 300 - 2 400 TND / mois',
                        'employability' => 'Élevé',
                    ],
                ]
            ],
            'sante' => [
                'domain_label' => 'Santé, Sciences Médicales & Paramédicales',
                'careers' => [
                    [
                        'title' => 'Médecin Généraliste ou Spécialiste',
                        'description' => 'Assure le diagnostic, le traitement et le suivi médical des patients, en cabinet privé ou dans les structures hospitalières.',
                        'secteurs' => ['Hôpitaux Publics Tunisiens', 'Cliniques Privées (Tunis, Sousse, Sfax)', 'Cabinets médicaux libéraux', 'Centres de réadaptation'],
                        'skills_hard' => ['Diagnostic clinique', 'Pharmacologie médicale', 'Urgences médicales', 'Suivi pathologique'],
                        'skills_soft' => ['Empathie & Écoute active', 'Résistance physique et mentale', 'Prise de décision rapide', 'Éthique professionnelle'],
                        'salary_range' => '1 800 - 3 500 TND / mois (Secteur public/Débutant)',
                        'employability' => 'Très Élevé',
                    ],
                    [
                        'title' => 'Pharmacien (d\'officine, industriel ou hospitalier)',
                        'description' => 'Gère la dispensation des médicaments, conseille les patients ou participe à la recherche et à la fabrication de produits pharmaceutiques.',
                        'secteurs' => ['Officines de pharmacie privées', 'Laboratoires pharmaceutiques tunisiens (SIPHAT, Opalia, etc.)', 'Grossistes répartiteurs'],
                        'skills_hard' => ['Chimie organique et thérapeutique', 'Gestion d\'officine', 'Réglementation des produits de santé', 'Pharmacovigilance'],
                        'skills_soft' => ['Sens du service client', 'Rigueur scientifique', 'Organisation', 'Précision'],
                        'salary_range' => '1 400 - 2 500 TND / mois',
                        'employability' => 'Élevé',
                    ],
                    [
                        'title' => 'Kinésithérapeute & Rééducateur Fonctionnel',
                        'description' => 'Conçoit et met en œuvre des programmes de rééducation physique pour restaurer la mobilité et réduire la douleur.',
                        'secteurs' => ['Centres de thalassothérapie (Djerba, Hammamet)', 'Hôpitaux et cabinets de kinésithérapie', 'Fédérations sportives'],
                        'skills_hard' => ['Anatomie & Biomécanique', 'Techniques de massage et de manipulation', 'Équipement de rééducation', 'Physiothérapie'],
                        'skills_soft' => ['Empathie', 'Sens du contact physique', 'Patience', 'Encouragement et motivation'],
                        'salary_range' => '900 - 1 600 TND / mois',
                        'employability' => 'Modéré',
                    ],
                ]
            ],
            'technique' => [
                'domain_label' => 'Génie Ingénierie & Métiers Industriels',
                'careers' => [
                    [
                        'title' => 'Ingénieur Électromécanique / Maintenance Industrielle',
                        'description' => 'Supervise la maintenance préventive et corrective des systèmes mécaniques et électroniques automatisés.',
                        'secteurs' => ['Zones industrielles (Mghira, Charguia, Sfax)', 'Cimenteries et Usines chimiques', 'Compagnies pétrolières', 'STEG / SONEDE'],
                        'skills_hard' => ['Automates programmables (Siemens, Schneider)', 'Lecture de schémas électriques', 'Mécanique des fluides', 'Logiciels de GMAO'],
                        'skills_soft' => ['Esprit d\'investigation', 'Gestion du stress en cas de panne', 'Leadership d\'équipe', 'Réactivité'],
                        'salary_range' => '1 200 - 2 000 TND / mois',
                        'employability' => 'Élevé',
                    ],
                    [
                        'title' => 'Ingénieur Génie Civil & Conducteur de Travaux',
                        'description' => 'Pilote les chantiers de construction de bâtiments ou d\'infrastructures publiques, en veillant au respect des coûts et des délais.',
                        'secteurs' => ['Bureaux d\'études techniques', 'Entreprises de BTP (Bâtiment et Travaux Publics)', 'Municipalités', 'Ministère de l\'Équipement'],
                        'skills_hard' => ['Calcul de structures (Robot, AutoCAD)', 'Planification de chantier (MS Project)', 'Topographie', 'Réglementation de sécurité'],
                        'skills_soft' => ['Autorité naturelle', 'Sens des responsabilités', 'Négociation commerciale', 'Adaptabilité terrain'],
                        'salary_range' => '1 100 - 1 800 TND / mois',
                        'employability' => 'Modéré',
                    ],
                ]
            ],
            'sciences' => [
                'domain_label' => 'Sciences Fondamentales, Laboratoire & Recherche',
                'careers' => [
                    [
                        'title' => 'Technicien de Laboratoire d\'Analyses Médicales',
                        'description' => 'Réalise les analyses biologiques et physico-chimiques sur des échantillons biologiques sous la responsabilité du médecin biologiste.',
                        'secteurs' => ['Laboratoires d\'analyses privés', 'Hôpitaux et cliniques', 'Centres de transfusion sanguine', 'Laboratoires agroalimentaires'],
                        'skills_hard' => ['Prélèvements sanguins', 'Utilisation d\'automates de laboratoire', 'Biochimie & Hématologie', 'Normes de biosécurité'],
                        'skills_soft' => ['Rigueur absolue', 'Précision', 'Hygiène irréprochable', 'Discrétion (secret médical)'],
                        'salary_range' => '800 - 1 400 TND / mois',
                        'employability' => 'Modéré',
                    ],
                    [
                        'title' => 'Responsable Contrôle Qualité (Agroalimentaire/Chimique)',
                        'description' => 'Vérifie que les matières premières et les produits finis sont conformes aux normes tunisiennes et internationales de sécurité et d\'hygiène.',
                        'secteurs' => ['Industries agroalimentaires (Délice, SFBT, etc.)', 'Laboratoires cosmétiques et chimiques', 'Organismes de certification (SGS)'],
                        'skills_hard' => ['Analyse microbiologique', 'Normes ISO (9001, 22000)', 'Méthodes de chromatographie', 'HACCP'],
                        'skills_soft' => ['Fermeté', 'Esprit d\'observation', 'Rigueur', 'Sens de la diplomatie'],
                        'salary_range' => '950 - 1 600 TND / mois',
                        'employability' => 'Élevé',
                    ],
                ]
            ],
            'economie' => [
                'domain_label' => 'Économie, Gestion, Finance & Commerce',
                'careers' => [
                    [
                        'title' => 'Auditeur Financier & Collaborateur Comptable',
                        'description' => 'Examine les comptes des entreprises tunisiennes pour en certifier la régularité et la sincérité vis-à-vis du fisc et des actionnaires.',
                        'secteurs' => ['Cabinets d\'expertise comptable (Big Four et cabinets locaux)', 'Directions financières d\'entreprises', 'Secteur public'],
                        'skills_hard' => ['Comptabilité générale tunisienne', 'Fiscalité des entreprises', 'Droit des affaires', 'Analyse financière'],
                        'skills_soft' => ['Rigueur mathématique', 'Sens de l\'éthique et de la discrétion', 'Capacité de travail importante', 'Sens du détail'],
                        'salary_range' => '900 - 1 700 TND / mois (Cabinet/Débutant)',
                        'employability' => 'Élevé',
                    ],
                    [
                        'title' => 'Conseiller Clientèle en Banque / Chargé d\'Affaires',
                        'description' => 'Gère et développe un portefeuille de clients particuliers ou professionnels en leur proposant des produits de financement et d\'épargne.',
                        'secteurs' => ['Agences Bancaires (BIAT, Attijari, BH, BNA, etc.)', 'Compagnies d\'assurances', 'Établissements de leasing'],
                        'skills_hard' => ['Techniques de vente et négociation', 'Analyse de dossier de crédit', 'Réglementation bancaire BCT', 'Gestion de la relation client (CRM)'],
                        'skills_soft' => ['Aisance relationnelle', 'Persuasion', 'Sens du résultat', 'Écoute'],
                        'salary_range' => '1 100 - 1 800 TND / mois',
                        'employability' => 'Stable',
                    ],
                ]
            ],
            'lettres' => [
                'domain_label' => 'Lettres, Traduction, Enseignement & Langues',
                'careers' => [
                    [
                        'title' => 'Traducteur / Rédacteur de Contenu Bilingue',
                        'description' => 'Traduit des documents officiels, techniques ou marketing pour des clients tunisiens et internationaux, ou rédige du contenu optimisé pour le web.',
                        'secteurs' => ['Agences de communication web', 'Bureaux de traduction assermentés', 'Plateformes de Freelance (Télétravail)', 'Maisons d\'édition'],
                        'skills_hard' => ['Maîtrise parfaite des langues (Arabe, Français, Anglais)', 'Techniques de traduction et rédaction', 'SEO de base', 'Correction orthographique'],
                        'skills_soft' => ['Rigueur linguistique', 'Sens esthétique du texte', 'Curiosité culturelle', 'Respect des délais'],
                        'salary_range' => '800 - 1 500 TND / mois',
                        'employability' => 'Modéré',
                    ],
                    [
                        'title' => 'Enseignant de Langues (Primaire / Secondaire)',
                        'description' => 'Transmet les connaissances linguistiques et littéraires aux élèves tout en assurant leur développement pédagogique.',
                        'secteurs' => ['Écoles et lycées privés', 'Centres de formation linguistique (AMIDEAST, Institut Français)', 'Ministère de l\'Éducation (Secteur public)'],
                        'skills_hard' => ['Pédagogie de l\'enseignement', 'Gestion de classe', 'Évaluation scolaire', 'Création de supports didactiques'],
                        'skills_soft' => ['Patience infinie', 'Excellente communication orale', 'Créativité pédagogique', 'Autorité bienveillante'],
                        'salary_range' => '800 - 1 300 TND / mois (Secteur privé)',
                        'employability' => 'Stable',
                    ],
                ]
            ],
            'social' => [
                'domain_label' => 'Sciences Sociales, Juridiques & Éducation',
                'careers' => [
                    [
                        'title' => 'Avocat d\'Affaires ou Collaborateur Juridique',
                        'description' => 'Conseille les entreprises sur le plan juridique et les défend en cas de litiges devant les tribunaux tunisiens.',
                        'secteurs' => ['Cabinets d\'avocats d\'affaires', 'Directions juridiques de grandes entreprises (Banques, Télécoms)', 'Fonction publique'],
                        'skills_hard' => ['Droit commercial tunisien', 'Droit du travail', 'Rédaction d\'actes juridiques', 'Plaidoyer et procédure civile'],
                        'skills_soft' => ['Aisance oratoire remarquable', 'Sens de la négociation', 'Combativité intellectuelle', 'Intégrité'],
                        'salary_range' => '800 - 1 500 TND / mois (Début de stage/Cabinet)',
                        'employability' => 'Stable',
                    ],
                    [
                        'title' => 'Conseiller en Orientation / Accompagnateur Social',
                        'description' => 'Aide les jeunes et les chercheurs d\'emploi à définir leur projet professionnel en utilisant des outils d\'évaluation et des entretiens.',
                        'secteurs' => ['Centres d\'orientation professionnelle (ANETI)', 'Établissements scolaires et universitaires', 'Associations et ONG', 'Cabinets de RH'],
                        'skills_hard' => ['Techniques d\'entretien', 'Tests psychométriques', 'Connaissance du marché de l\'emploi', 'Gestion de dossiers'],
                        'skills_soft' => ['Empathie profonde', 'Écoute active', 'Patience', 'Absence de jugement'],
                        'salary_range' => '850 - 1 300 TND / mois',
                        'employability' => 'Stable',
                    ],
                ]
            ],
            'arts' => [
                'domain_label' => 'Arts, Design, Architecture & Communication',
                'careers' => [
                    [
                        'title' => 'Designer Graphique / Designer UI-UX',
                        'description' => 'Crée l\'identité visuelle des marques ou conçoit des interfaces numériques conviviales et esthétiques pour des sites web et applications mobiles.',
                        'secteurs' => ['Agences de communication & publicité (Grand Tunis)', 'Studios de création numérique', 'Startups technologiques', 'Freelance'],
                        'skills_hard' => ['Suite Adobe (Photoshop, Illustrator)', 'Figma / Sketch', 'Principes d\'ergonomie web', 'Design adaptatif (Responsive)'],
                        'skills_soft' => ['Créativité et curiosité', 'Sensibilité artistique', 'Esprit d\'écoute des besoins client', 'Adaptabilité aux tendances'],
                        'salary_range' => '900 - 1 800 TND / mois',
                        'employability' => 'Forte Croissance',
                    ],
                    [
                        'title' => 'Architecte d\'Intérieur / Concepteur d\'Espace',
                        'description' => 'Conçoit des projets d\'aménagement intérieur esthétiques, confortables et fonctionnels pour des résidences ou des espaces professionnels.',
                        'secteurs' => ['Bureaux d\'architecture', 'Showrooms de mobilier', 'Promoteurs immobiliers', 'Activité indépendante'],
                        'skills_hard' => ['Outils de modélisation 3D (3ds Max, SketchUp, Revit)', 'Règles de sécurité bâtiment', 'Estimation de devis de travaux', 'Sélection des matériaux'],
                        'skills_soft' => ['Sens esthétique développé', 'Organisation rigoureuse', 'Négociation fournisseurs', 'Prise en compte des contraintes budget'],
                        'salary_range' => '1 000 - 1 700 TND / mois',
                        'employability' => 'Stable',
                    ],
                ]
            ],
            'default' => [
                'domain_label' => 'Carrières Polyvalentes & Secteurs Divers',
                'careers' => [
                    [
                        'title' => 'Chargé d\'Administration et de Gestion administrative',
                        'description' => 'Assure le secrétariat, la gestion des dossiers du personnel et le suivi de la facturation courante.',
                        'secteurs' => ['PME et PMI tunisiennes de tous secteurs', 'Administrations et associations'],
                        'skills_hard' => ['Outils bureautiques (Word, Excel)', 'Gestion de courriers et d\'agendas', 'Bases de comptabilité simple', 'Classement numérique'],
                        'skills_soft' => ['Sens de l\'organisation', 'Rigueur', 'Amabilité et bon relationnel', 'Polyvalence'],
                        'salary_range' => '700 - 1 200 TND / mois',
                        'employability' => 'Stable',
                    ]
                ]
            ]
        ];

        return $data[$domain] ?? $data['default'];
    }
}
