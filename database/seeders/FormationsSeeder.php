<?php

namespace Database\Seeders;

use App\Models\Specialite;
use App\Models\Formation;
use Illuminate\Database\Seeder;

class FormationsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création des Spécialités
        $specsData = [
            [
                'id' => 1,
                'nom' => 'Génie Logiciel',
                'description' => 'Conception, développement et maintenance d\'applications logicielles complexes et de systèmes informatiques.',
                'domaine' => 'Informatique',
                'icon' => '💻',
                'color' => 'indigo',
                'nb_formations' => 2
            ],
            [
                'id' => 2,
                'nom' => 'IA & Science des Données',
                'description' => 'Analyse de données massives, développement de modèles d\'apprentissage automatique et d\'algorithmes d\'intelligence artificielle.',
                'domaine' => 'Informatique',
                'icon' => '🤖',
                'color' => 'cyan',
                'nb_formations' => 2
            ],
            [
                'id' => 3,
                'nom' => 'Cybersécurité',
                'description' => 'Protection des systèmes d\'information, détection des menaces, cryptographie et audit de sécurité des infrastructures.',
                'domaine' => 'Informatique',
                'icon' => '🔒',
                'color' => 'red',
                'nb_formations' => 2
            ],
            [
                'id' => 4,
                'nom' => 'Médecine Générale',
                'description' => 'Diagnostic, traitement et suivi des patients en santé humaine. Formation clinique et théorique approfondie.',
                'domaine' => 'Sciences Expérimentales',
                'icon' => '🩺',
                'color' => 'green',
                'nb_formations' => 1
            ],
            [
                'id' => 5,
                'nom' => 'Génie Civil & Structure',
                'description' => 'Conception et gestion de projets de construction de bâtiments, ponts, routes et infrastructures publiques.',
                'domaine' => 'Technologie',
                'icon' => '🏗️',
                'color' => 'orange',
                'nb_formations' => 1
            ],
            [
                'id' => 6,
                'nom' => 'Finance & Comptabilité',
                'description' => 'Gestion financière des entreprises, audit, analyse de marché, investissements et comptabilité supérieure.',
                'domaine' => 'Économie et Gestion',
                'icon' => '📈',
                'color' => 'gold',
                'nb_formations' => 2
            ],
        ];

        foreach ($specsData as $s) {
            Specialite::updateOrCreate(['id' => $s['id']], $s);
        }

        // 2. Création des Formations
        $formationsData = [
            // Génie Logiciel
            [
                'specialite_id' => 1,
                'nom' => 'Diplôme d\'Ingénieur en Génie Logiciel',
                'etablissement' => 'ESPRIT',
                'ville' => 'Ariana (Tunis)',
                'duree' => '3 ans',
                'niveau' => 'Ingénierie',
                'description' => 'Formation d\'ingénieurs opérationnels spécialisés dans le développement d\'applications mobiles, web et cloud. Forte orientation projet et partenariats industriels.',
                'debouches' => 'Ingénieur Fullstack, Architecte Logiciel, DevOps Engineer, Chef de Projet IT.',
                'conditions_acces' => 'Admissibilité sur dossier après un cycle préparatoire scientifique (MP/PC/PT) ou Licence en Informatique.',
                'salaire_min' => '1800',
                'salaire_max' => '3500',
                'secteur' => 'Privé / Public',
                'icon' => '💻',
                'score_matching' => 92
            ],
            [
                'specialite_id' => 1,
                'nom' => 'Licence en Sciences de l\'Informatique',
                'etablissement' => 'Faculté des Sciences de Tunis (FST)',
                'ville' => 'Tunis',
                'duree' => '3 ans',
                'niveau' => 'Licence',
                'description' => 'Formation académique solide couvrant les fondements de l\'informatique : algorithmique, structures de données, bases de données, réseaux et systèmes.',
                'debouches' => 'Développeur d\'applications, Administrateur de bases de données, Technicien réseaux.',
                'conditions_acces' => 'Baccalauréat en Informatique, Mathématiques ou Sciences Expérimentales.',
                'salaire_min' => '1100',
                'salaire_max' => '2200',
                'secteur' => 'Privé / Public',
                'icon' => '🖥️',
                'score_matching' => 78
            ],

            // IA & Data Science
            [
                'specialite_id' => 2,
                'nom' => 'Diplôme d\'Ingénieur en IA & Data Science',
                'etablissement' => 'École Nationale des Sciences de l\'Informatique (ENSI)',
                'ville' => 'La Manouba',
                'duree' => '3 ans',
                'niveau' => 'Ingénierie',
                'description' => 'Formation d\'élite en conception d\'algorithmes d\'intelligence artificielle, traitement automatique du langage naturel (NLP), vision par ordinateur et big data.',
                'debouches' => 'Data Scientist, Ingénieur Machine Learning, Data Engineer, Chercheur en IA.',
                'conditions_acces' => 'Concours national d\'entrée aux écoles d\'ingénieurs (très sélectif).',
                'salaire_min' => '2200',
                'salaire_max' => '4500',
                'secteur' => 'Privé / International / R&D',
                'icon' => '🤖',
                'score_matching' => 95
            ],
            [
                'specialite_id' => 2,
                'nom' => 'Master de Recherche en Intelligence Artificielle',
                'etablissement' => 'Institut National des Sciences Appliquées et de Technologie (INSAT)',
                'ville' => 'Tunis',
                'duree' => '2 ans',
                'niveau' => 'Master',
                'description' => 'Master axé sur la recherche académique et l\'innovation en apprentissage profond, réseaux de neurones, et mathématiques appliquées aux données.',
                'debouches' => 'Chercheur en IA, Data Analyst senior, Poursuite en Doctorat.',
                'conditions_acces' => 'Licence en Informatique ou Mathématiques Appliquées avec mention.',
                'salaire_min' => '1500',
                'salaire_max' => '3000',
                'secteur' => 'R&D / Enseignement / Privé',
                'icon' => '📊',
                'score_matching' => 85
            ],

            // Cybersécurité
            [
                'specialite_id' => 3,
                'nom' => 'Diplôme d\'Ingénieur en Sécurité Informatique',
                'etablissement' => 'INSAT',
                'ville' => 'Tunis',
                'duree' => '3 ans',
                'niveau' => 'Ingénierie',
                'description' => 'Formation d\'ingénieurs experts en sécurité des réseaux, sécurité des applications web/mobiles, cryptographie, hacking éthique et conformité réglementaire.',
                'debouches' => 'Consultant en Cybersécurité, Pentester, Analyste SOC, Responsable de la Sécurité des Systèmes d\'Information (RSSI).',
                'conditions_acces' => 'Cycle préparatoire intégré de l\'INSAT (filière MPI) ou concours sur dossier.',
                'salaire_min' => '2300',
                'salaire_max' => '5000',
                'secteur' => 'Privé / International / Télécoms',
                'icon' => '🔒',
                'score_matching' => 93
            ],
            [
                'specialite_id' => 3,
                'nom' => 'Licence en Sécurité des Systèmes Informatiques',
                'etablissement' => 'Institut des Supérieur d\'Informatique (ISI)',
                'ville' => 'Ariana',
                'duree' => '3 ans',
                'niveau' => 'Licence',
                'description' => 'Acquisition des compétences pratiques pour configurer des pare-feu, gérer des serveurs sécurisés, administrer les réseaux et implémenter des politiques de sécurité de base.',
                'debouches' => 'Administrateur Sécurité, Technicien Support Sécurité, Développeur Sécurisé.',
                'conditions_acces' => 'Baccalauréat Informatique, Mathématiques ou Sciences Expérimentales.',
                'salaire_min' => '1200',
                'salaire_max' => '2400',
                'secteur' => 'Privé / PME',
                'icon' => '🛡️',
                'score_matching' => 80
            ],

            // Médecine
            [
                'specialite_id' => 4,
                'nom' => 'Diplôme National de Docteur en Médecine',
                'etablissement' => 'Faculté de Médecine de Tunis',
                'ville' => 'Tunis',
                'duree' => '6 ans',
                'niveau' => 'Doctorat',
                'description' => 'Formation théorique et clinique complète de médecin généraliste, incluant des stages hospitaliers intensifs et la préparation de la thèse de doctorat.',
                'debouches' => 'Médecin Généraliste (secteur libre ou public), Préparation au concours de Résidanat pour spécialisation.',
                'conditions_acces' => 'Baccalauréat Sciences Expérimentales ou Mathématiques avec un excellent score SDO (très sélectif).',
                'salaire_min' => '1500',
                'salaire_max' => '4000',
                'secteur' => 'Santé Publique / Clinique Privée / Cabinet',
                'icon' => '🩺',
                'score_matching' => 88
            ],

            // Génie Civil
            [
                'specialite_id' => 5,
                'nom' => 'Diplôme d\'Ingénieur en Génie Civil',
                'etablissement' => 'École Nationale d\'Ingénieurs de Tunis (ENIT)',
                'ville' => 'Tunis',
                'duree' => '3 ans',
                'niveau' => 'Ingénierie',
                'description' => 'Formation d\'ingénieurs de haut niveau en calcul de structures, géotechnique, hydraulique civile et management des chantiers de construction.',
                'debouches' => 'Ingénieur Structure, Chef de Projet Construction, Ingénieur Géotechnique, Inspecteur de Chantiers.',
                'conditions_acces' => 'Concours national d\'entrée aux écoles d\'ingénieurs.',
                'salaire_min' => '1300',
                'salaire_max' => '2800',
                'secteur' => 'Bureaux d\'études / Entreprises de BTP / Public',
                'icon' => '🏗️',
                'score_matching' => 83
            ],

            // Finance & Gestion
            [
                'specialite_id' => 6,
                'nom' => 'Licence en Finance',
                'etablissement' => 'Institut des Hautes Études Commerciales (IHEC Carthage)',
                'ville' => 'Carthage (Tunis)',
                'duree' => '3 ans',
                'niveau' => 'Licence',
                'description' => 'Maîtrise des principes de finance de marché et de finance d\'entreprise, contrôle de gestion, analyse de crédit et décisions d\'investissement.',
                'debouches' => 'Analyste Financier junior, Conseiller Clientèle Banque, Auditeur junior, Gestionnaire de trésorerie.',
                'conditions_acces' => 'Baccalauréat Économie et Gestion, Mathématiques ou Informatique.',
                'salaire_min' => '1100',
                'salaire_max' => '2000',
                'secteur' => 'Banques / Assurances / Cabinets d\'Audit / Privé',
                'icon' => '📈',
                'score_matching' => 82
            ],
            [
                'specialite_id' => 6,
                'nom' => 'Master Professionnel en Gestion des Risques & Conformité',
                'etablissement' => 'Institut Supérieur de Gestion de Tunis (ISG)',
                'ville' => 'Tunis',
                'duree' => '2 ans',
                'niveau' => 'Master',
                'description' => 'Formation spécialisée dans l\'évaluation et la gestion des risques financiers, réglementaires et opérationnels dans les institutions bancaires et d\'assurance.',
                'debouches' => 'Risk Manager, Chargé de conformité (Compliance Officer), Auditeur interne.',
                'conditions_acces' => 'Licence en Gestion, Économie ou diplôme équivalent après étude de dossier.',
                'salaire_min' => '1400',
                'salaire_max' => '2600',
                'secteur' => 'Banques / Assurances / Multinationales',
                'icon' => '💼',
                'score_matching' => 86
            ],
        ];

        foreach ($formationsData as $f) {
            Formation::updateOrCreate(
                [
                    'specialite_id' => $f['specialite_id'],
                    'nom' => $f['nom'],
                    'etablissement' => $f['etablissement']
                ],
                $f
            );
        }
    }
}
