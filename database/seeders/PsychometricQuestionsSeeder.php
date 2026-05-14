<?php

namespace Database\Seeders;

use App\Models\QuestionRiasec;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PsychometricQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        QuestionRiasec::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $questions = array_merge(
            $this->riasecQuestions(),
            $this->intraDomainQuestions(),
            $this->gatbQuestions(),
            $this->bigFiveQuestions(),
            $this->schwartzQuestions(),
            $this->cddqQuestions(),
            $this->parcoursScolaireQuestions()
        );

        $ordre = 1;
        foreach ($questions as $q) {
            QuestionRiasec::create(array_merge($q, [
                'ordre'              => $ordre++,
                'actif'              => true,
                'type_reponse'       => 'likert',
                'poids'              => 1,
                'discrimination'     => $q['discrimination'] ?? rand(60, 95) / 10.0,
                'difficulty'         => $q['difficulty'] ?? (rand(-15, 15) / 10.0),
                'is_reverse'         => $q['is_reverse'] ?? false,
                'version'            => '2.1',
                'is_seed'            => true,
                'source'             => $q['source'] ?? 'CapAvenir v2.1',
            ]));
        }

        $this->command->info('✅ ' . QuestionRiasec::count() . ' questions psychométriques insérées.');
    }

    /* ══════════════════════════════════════════════════
       PARTIE 1 : INTÉRÊTS RIASEC (24 questions)
    ══════════════════════════════════════════════════ */
    private function riasecQuestions(): array
    {
        return [
            // R - Réaliste
            ['bloc'=>'riasec', 'dimension'=>'R', 'categorie'=>'preferences', 'texte_fr'=>'J’aime travailler avec des machines, des outils ou des équipements techniques.', 'source'=>'Holland (1997)'],
            ['bloc'=>'riasec', 'dimension'=>'R', 'categorie'=>'preferences', 'texte_fr'=>'Je préfère les activités physiques et le travail manuel aux tâches de bureau.', 'source'=>'Holland (1997)'],
            ['bloc'=>'riasec', 'dimension'=>'R', 'categorie'=>'preferences', 'texte_fr'=>'J’aime construire, réparer ou assembler des objets.', 'source'=>'Holland (1997)'],
            ['bloc'=>'riasec', 'dimension'=>'R', 'categorie'=>'preferences', 'texte_fr'=>'Je me sens bien en travaillant en extérieur (chantier, nature, agriculture).', 'source'=>'Holland (1997)'],
            
            // I - Investigateur
            ['bloc'=>'riasec', 'dimension'=>'I', 'categorie'=>'preferences', 'texte_fr'=>'J’aime résoudre des problèmes scientifiques ou mathématiques complexes.', 'source'=>'Holland (1997)'],
            ['bloc'=>'riasec', 'dimension'=>'I', 'categorie'=>'preferences', 'texte_fr'=>'Je suis curieux(se) de comprendre comment fonctionnent les choses (corps, machines, systèmes).', 'source'=>'Holland (1997)'],
            ['bloc'=>'riasec', 'dimension'=>'I', 'categorie'=>'preferences', 'texte_fr'=>'J’aime faire des expériences en laboratoire ou analyser des données.', 'source'=>'Holland (1997)'],
            ['bloc'=>'riasec', 'dimension'=>'I', 'categorie'=>'loisirs', 'texte_fr'=>'Je lis des articles scientifiques ou techniques par plaisir.', 'source'=>'Holland (1997)'],
            
            // A - Artistique
            ['bloc'=>'riasec', 'dimension'=>'A', 'categorie'=>'preferences', 'texte_fr'=>'J’aime créer des œuvres originales (dessin, musique, écriture, design).', 'source'=>'Holland (1997)'],
            ['bloc'=>'riasec', 'dimension'=>'A', 'categorie'=>'preferences', 'texte_fr'=>'Je préfère un travail créatif sans routine fixe.', 'source'=>'Holland (1997)'],
            ['bloc'=>'riasec', 'dimension'=>'A', 'categorie'=>'preferences', 'texte_fr'=>'J’aime m’exprimer à travers l’art, le théâtre, la danse ou les médias.', 'source'=>'Holland (1997)'],
            ['bloc'=>'riasec', 'dimension'=>'A', 'categorie'=>'preferences', 'texte_fr'=>'Je suis très sensible à l’esthétique, aux couleurs et aux formes.', 'source'=>'Holland (1997)'],
            
            // S - Social
            ['bloc'=>'riasec', 'dimension'=>'S', 'categorie'=>'preferences', 'texte_fr'=>'J’aime aider, conseiller, enseigner ou soigner les autres.', 'source'=>'Holland (1997)'],
            ['bloc'=>'riasec', 'dimension'=>'S', 'categorie'=>'preferences', 'texte_fr'=>'Je préfère travailler en équipe plutôt que seul(e).', 'source'=>'Holland (1997)'],
            ['bloc'=>'riasec', 'dimension'=>'S', 'categorie'=>'preferences', 'texte_fr'=>'J’aime écouter les autres et les soutenir dans leurs difficultés.', 'source'=>'Holland (1997)'],
            ['bloc'=>'riasec', 'dimension'=>'S', 'categorie'=>'preferences', 'texte_fr'=>'Les métiers qui ont une forte dimension humaine (santé, éducation, social) m’attirent beaucoup.', 'source'=>'Holland (1997)'],
            
            // E - Entreprenant
            ['bloc'=>'riasec', 'dimension'=>'E', 'categorie'=>'preferences', 'texte_fr'=>'J’aime convaincre, négocier, vendre ou diriger des projets.', 'source'=>'Holland (1997)'],
            ['bloc'=>'riasec', 'dimension'=>'E', 'categorie'=>'preferences', 'texte_fr'=>'J’ai le goût du risque et des défis ambitieux.', 'source'=>'Holland (1997)'],
            ['bloc'=>'riasec', 'dimension'=>'E', 'categorie'=>'preferences', 'texte_fr'=>'Je suis à l’aise pour parler en public et diriger un groupe.', 'source'=>'Holland (1997)'],
            ['bloc'=>'riasec', 'dimension'=>'E', 'categorie'=>'preferences', 'texte_fr'=>'J’aime la compétition et la réussite sociale.', 'source'=>'Holland (1997)'],
            
            // C - Conventionnel
            ['bloc'=>'riasec', 'dimension'=>'C', 'categorie'=>'preferences', 'texte_fr'=>'J’aime classer, organiser des informations et travailler avec des données.', 'source'=>'Holland (1997)'],
            ['bloc'=>'riasec', 'dimension'=>'C', 'categorie'=>'preferences', 'texte_fr'=>'Je préfère suivre des procédures précises et des règles claires.', 'source'=>'Holland (1997)'],
            ['bloc'=>'riasec', 'dimension'=>'C', 'categorie'=>'preferences', 'texte_fr'=>'Je me sens bien en travaillant avec des chiffres et des tableaux.', 'source'=>'Holland (1997)'],
            ['bloc'=>'riasec', 'dimension'=>'C', 'categorie'=>'preferences', 'texte_fr'=>'Je suis méthodique, rigoureux(se) et attentif(ve) aux détails.', 'source'=>'Holland (1997)'],
        ];
    }

    /* ══════════════════════════════════════════════════
       PARTIE 2 : DISCRIMINATION INTRA-DOMAINE (34 questions)
    ══════════════════════════════════════════════════ */
    private function intraDomainQuestions(): array
    {
        return [
            // Santé & Paramédical
            ['bloc'=>'intra', 'dimension'=>'SANTE', 'categorie'=>'intra', 'texte_fr'=>'Je veux diagnostiquer des maladies et prescrire des traitements.'],
            ['bloc'=>'intra', 'dimension'=>'SANTE', 'categorie'=>'intra', 'texte_fr'=>'Je suis passionné(e) par les médicaments, leur composition et leurs effets.'],
            ['bloc'=>'intra', 'dimension'=>'SANTE', 'categorie'=>'intra', 'texte_fr'=>'Je suis attiré(e) par la chirurgie et les soins dentaires.'],
            ['bloc'=>'intra', 'dimension'=>'SANTE', 'categorie'=>'intra', 'texte_fr'=>'Je veux aider les patients par le mouvement, la rééducation et la kinésithérapie.'],
            ['bloc'=>'intra', 'dimension'=>'SANTE', 'categorie'=>'intra', 'texte_fr'=>'Je préfère les soins continus et la relation durable avec les patients.'],
            ['bloc'=>'intra', 'dimension'=>'SANTE', 'categorie'=>'intra', 'texte_fr'=>'Je suis intéressé(e) par la rééducation du langage et de la communication.'],
            ['bloc'=>'intra', 'dimension'=>'SANTE', 'categorie'=>'intra', 'texte_fr'=>'Je supporte bien la vue du sang, des opérations et des situations d’urgence.'],
            
            // SHS (Sciences Humaines & Sociales)
            ['bloc'=>'intra', 'dimension'=>'SHS', 'categorie'=>'intra', 'texte_fr'=>'Comprendre le comportement humain et la psychologie me passionne.'],
            ['bloc'=>'intra', 'dimension'=>'SHS', 'categorie'=>'intra', 'texte_fr'=>'J’aime étudier l’histoire, les civilisations et l’évolution des sociétés.'],
            ['bloc'=>'intra', 'dimension'=>'SHS', 'categorie'=>'intra', 'texte_fr'=>'Transmettre des connaissances et enseigner m’attire énormément.'],
            ['bloc'=>'intra', 'dimension'=>'SHS', 'categorie'=>'intra', 'texte_fr'=>'J’aimerais travailler dans le domaine du journalisme ou des médias.'],
            ['bloc'=>'intra', 'dimension'=>'SHS', 'categorie'=>'intra', 'texte_fr'=>'Je suis intéressé(e) par la résolution des problèmes sociaux et l’aide aux populations vulnérables.'],
            ['bloc'=>'intra', 'dimension'=>'SHS', 'categorie'=>'intra', 'texte_fr'=>'J’aime analyser comment la géographie et l’environnement influencent l’homme.'],

            // Langues
            ['bloc'=>'intra', 'dimension'=>'LANG', 'categorie'=>'intra', 'texte_fr'=>'J’ai des facilités pour apprendre et parler de nouvelles langues étrangères.'],
            ['bloc'=>'intra', 'dimension'=>'LANG', 'categorie'=>'intra', 'texte_fr'=>'La traduction et l’interprétation sont des domaines qui m’intéressent.'],
            ['bloc'=>'intra', 'dimension'=>'LANG', 'categorie'=>'intra', 'texte_fr'=>'J’aimerais travailler à l’international ou avec des personnes de différentes cultures.'],
            ['bloc'=>'intra', 'dimension'=>'LANG', 'categorie'=>'intra', 'texte_fr'=>'Analyser la littérature étrangère me plaît beaucoup.'],
            ['bloc'=>'intra', 'dimension'=>'LANG', 'categorie'=>'intra', 'texte_fr'=>'Je suis fasciné(e) par l’origine et l’évolution des mots.'],

            // Design & Arts
            ['bloc'=>'intra', 'dimension'=>'ART', 'categorie'=>'intra', 'texte_fr'=>'Je maîtrise ou j’aimerais maîtriser des logiciels de création graphique (Photoshop, Illustrator, etc.).'],
            ['bloc'=>'intra', 'dimension'=>'ART', 'categorie'=>'intra', 'texte_fr'=>'L’architecture, la conception d’espaces et la décoration intérieure m’attirent.'],
            ['bloc'=>'intra', 'dimension'=>'ART', 'categorie'=>'intra', 'texte_fr'=>'J’aime la production audiovisuelle, le cinéma ou la photographie.'],
            ['bloc'=>'intra', 'dimension'=>'ART', 'categorie'=>'intra', 'texte_fr'=>'Je suis créatif(ve) dans le domaine de la mode et du stylisme.'],
            ['bloc'=>'intra', 'dimension'=>'ART', 'categorie'=>'intra', 'texte_fr'=>'Je pratique un art plastique (dessin, peinture, sculpture) régulièrement.'],

            // Droit & Sciences Politiques
            ['bloc'=>'intra', 'dimension'=>'DROIT', 'categorie'=>'intra', 'texte_fr'=>'Je suis intéressé(e) par les lois, les règles de la société et la justice.'],
            ['bloc'=>'intra', 'dimension'=>'DROIT', 'categorie'=>'intra', 'texte_fr'=>'Défendre les droits des personnes ou des entreprises me motive.'],
            ['bloc'=>'intra', 'dimension'=>'DROIT', 'categorie'=>'intra', 'texte_fr'=>'Comprendre la politique, les relations internationales et la géopolitique me passionne.'],
            ['bloc'=>'intra', 'dimension'=>'DROIT', 'categorie'=>'intra', 'texte_fr'=>'Je suis à l’aise pour argumenter, débattre et convaincre avec logique.'],

            // Economie & Gestion
            ['bloc'=>'intra', 'dimension'=>'ECO', 'categorie'=>'intra', 'texte_fr'=>'Je m’intéresse au fonctionnement des marchés financiers et de l’économie mondiale.'],
            ['bloc'=>'intra', 'dimension'=>'ECO', 'categorie'=>'intra', 'texte_fr'=>'J’aimerais gérer une entreprise, son personnel et ses stratégies.'],
            ['bloc'=>'intra', 'dimension'=>'ECO', 'categorie'=>'intra', 'texte_fr'=>'Le marketing, la publicité et les stratégies de vente m’attirent.'],
            ['bloc'=>'intra', 'dimension'=>'ECO', 'categorie'=>'intra', 'texte_fr'=>'Gérer des budgets, faire de la comptabilité et de la finance ne me fait pas peur.'],

            // Informatique & Ingénierie
            ['bloc'=>'intra', 'dimension'=>'INFO', 'categorie'=>'intra', 'texte_fr'=>'J’aime programmer, coder et résoudre des problèmes algorithmiques.'],
            ['bloc'=>'intra', 'dimension'=>'INFO', 'categorie'=>'intra', 'texte_fr'=>'Je suis attiré(e) par l’intelligence artificielle, les données et la cybersécurité.'],
            ['bloc'=>'intra', 'dimension'=>'ING', 'categorie'=>'intra', 'texte_fr'=>'Je veux concevoir des systèmes, des structures ou des réseaux techniques.'],
        ];
    }

    /* ══════════════════════════════════════════════════
       PARTIE 3 : APTITUDES COGNITIVES (12 questions)
    ══════════════════════════════════════════════════ */
    private function gatbQuestions(): array
    {
        return [
            ['bloc'=>'gatb', 'dimension'=>'G', 'categorie'=>'aptitudes', 'texte_fr'=>'Je comprends rapidement les schémas logiques et numériques.', 'source'=>'GATB'],
            ['bloc'=>'gatb', 'dimension'=>'G', 'categorie'=>'aptitudes', 'texte_fr'=>'Je saisis facilement les concepts nouveaux expliqués en cours.', 'source'=>'GATB'],
            ['bloc'=>'gatb', 'dimension'=>'G', 'categorie'=>'aptitudes', 'texte_fr'=>'Je peux raisonner à partir de données incomplètes.', 'source'=>'GATB'],
            
            ['bloc'=>'gatb', 'dimension'=>'V', 'categorie'=>'aptitudes', 'texte_fr'=>'Je m’exprime clairement à l’oral comme à l’écrit.', 'source'=>'GATB'],
            ['bloc'=>'gatb', 'dimension'=>'V', 'categorie'=>'aptitudes', 'texte_fr'=>'Rédiger des rapports ou des synthèses ne me pose pas de problème.', 'source'=>'GATB'],
            ['bloc'=>'gatb', 'dimension'=>'V', 'categorie'=>'aptitudes', 'texte_fr'=>'Je comprends les nuances de sens dans les textes complexes.', 'source'=>'GATB'],

            ['bloc'=>'gatb', 'dimension'=>'N', 'categorie'=>'aptitudes', 'texte_fr'=>'Je fais des calculs mentaux rapidement et avec précision.', 'source'=>'GATB'],
            ['bloc'=>'gatb', 'dimension'=>'N', 'categorie'=>'aptitudes', 'texte_fr'=>'Les statistiques, les probabilités et les mathématiques ne m’effraient pas.', 'source'=>'GATB'],
            ['bloc'=>'gatb', 'dimension'=>'N', 'categorie'=>'aptitudes', 'texte_fr'=>'J’aime travailler avec des chiffres et des tableaux de données.', 'source'=>'GATB'],

            ['bloc'=>'gatb', 'dimension'=>'S', 'categorie'=>'aptitudes', 'texte_fr'=>'Je me repère facilement dans l’espace et j’ai le sens de l’orientation.', 'source'=>'GATB'],
            ['bloc'=>'gatb', 'dimension'=>'S', 'categorie'=>'aptitudes', 'texte_fr'=>'Je visualise mentalement des objets en 3D sans difficulté.', 'source'=>'GATB'],
            ['bloc'=>'gatb', 'dimension'=>'S', 'categorie'=>'aptitudes', 'texte_fr'=>'Lire des plans, des cartes ou des schémas techniques me vient naturellement.', 'source'=>'GATB'],
        ];
    }

    /* ══════════════════════════════════════════════════
       PARTIE 4 : PERSONNALITÉ BIG FIVE (20 questions)
    ══════════════════════════════════════════════════ */
    private function bigFiveQuestions(): array
    {
        return [
            // Ouverture (O)
            ['bloc'=>'big_five', 'dimension'=>'O', 'categorie'=>'personnalite', 'texte_fr'=>'Je suis curieux(se) et j’aime découvrir de nouvelles choses.', 'source'=>'NEO-PI-R'],
            ['bloc'=>'big_five', 'dimension'=>'O', 'categorie'=>'personnalite', 'texte_fr'=>'J’aime explorer des idées nouvelles, même si elles sont inhabituelles.', 'source'=>'NEO-PI-R'],
            ['bloc'=>'big_five', 'dimension'=>'O', 'categorie'=>'personnalite', 'texte_fr'=>'J’apprécie l’art, la culture et les expériences esthétiques.', 'source'=>'NEO-PI-R'],
            ['bloc'=>'big_five', 'dimension'=>'O', 'categorie'=>'personnalite', 'texte_fr'=>'Je suis imaginatif(ve) et créatif(ve).', 'source'=>'NEO-PI-R'],

            // Conscienciosité (C)
            ['bloc'=>'big_five', 'dimension'=>'C', 'categorie'=>'personnalite', 'texte_fr'=>'Je suis organisé(e) et je termine toujours ce que je commence.', 'source'=>'NEO-PI-R'],
            ['bloc'=>'big_five', 'dimension'=>'C', 'categorie'=>'personnalite', 'texte_fr'=>'Je préfère planifier à l’avance plutôt qu’improviser.', 'source'=>'NEO-PI-R'],
            ['bloc'=>'big_five', 'dimension'=>'C', 'categorie'=>'personnalite', 'texte_fr'=>'Je suis rigoureux(se) et je fais attention aux détails.', 'source'=>'NEO-PI-R'],
            ['bloc'=>'big_five', 'dimension'=>'C', 'categorie'=>'personnalite', 'texte_fr'=>'Je suis discipliné(e) dans mon travail.', 'source'=>'NEO-PI-R'],

            // Extraversion (E)
            ['bloc'=>'big_five', 'dimension'=>'E', 'categorie'=>'personnalite', 'texte_fr'=>'Je me sens à l’aise en public et je prends facilement la parole.', 'source'=>'NEO-PI-R'],
            ['bloc'=>'big_five', 'dimension'=>'E', 'categorie'=>'personnalite', 'texte_fr'=>'Je préfère travailler en équipe plutôt que seul(e).', 'source'=>'NEO-PI-R'],
            ['bloc'=>'big_five', 'dimension'=>'E', 'categorie'=>'personnalite', 'texte_fr'=>'Je suis énergique et sociable.', 'source'=>'NEO-PI-R'],
            ['bloc'=>'big_five', 'dimension'=>'E', 'categorie'=>'personnalite', 'texte_fr'=>'Je recherche souvent les interactions sociales.', 'source'=>'NEO-PI-R'],

            // Agréabilité (A)
            ['bloc'=>'big_five', 'dimension'=>'A', 'categorie'=>'personnalite', 'texte_fr'=>'Je suis sensible aux émotions des autres.', 'source'=>'NEO-PI-R'],
            ['bloc'=>'big_five', 'dimension'=>'A', 'categorie'=>'personnalite', 'texte_fr'=>'Je préfère coopérer plutôt que rivaliser.', 'source'=>'NEO-PI-R'],
            ['bloc'=>'big_five', 'dimension'=>'A', 'categorie'=>'personnalite', 'texte_fr'=>'Je fais facilement confiance aux gens.', 'source'=>'NEO-PI-R'],
            ['bloc'=>'big_five', 'dimension'=>'A', 'categorie'=>'personnalite', 'texte_fr'=>'Je suis bienveillant(e) et j’évite les conflits.', 'source'=>'NEO-PI-R'],

            // Stabilité Emotionnelle (N inversé)
            ['bloc'=>'big_five', 'dimension'=>'N', 'categorie'=>'personnalite', 'texte_fr'=>'Je gère bien le stress et reste calme dans les situations difficiles.', 'source'=>'NEO-PI-R', 'is_reverse'=>true],
            ['bloc'=>'big_five', 'dimension'=>'N', 'categorie'=>'personnalite', 'texte_fr'=>'Je me remets rapidement après un échec.', 'source'=>'NEO-PI-R', 'is_reverse'=>true],
            ['bloc'=>'big_five', 'dimension'=>'N', 'categorie'=>'personnalite', 'texte_fr'=>'Je ne me laisse pas facilement submerger par mes émotions.', 'source'=>'NEO-PI-R', 'is_reverse'=>true],
            ['bloc'=>'big_five', 'dimension'=>'N', 'categorie'=>'personnalite', 'texte_fr'=>'Je suis généralement de bonne humeur.', 'source'=>'NEO-PI-R', 'is_reverse'=>true],
        ];
    }

    /* ══════════════════════════════════════════════════
       PARTIE 5 : VALEURS PROFESSIONNELLES (8 questions)
    ══════════════════════════════════════════════════ */
    private function schwartzQuestions(): array
    {
        return [
            ['bloc'=>'schwartz', 'dimension'=>'Sec', 'categorie'=>'valeurs', 'texte_fr'=>'La sécurité et la stabilité de l\'emploi sont essentielles pour moi.', 'source'=>'Schwartz SVS'],
            ['bloc'=>'schwartz', 'dimension'=>'Sec', 'categorie'=>'valeurs', 'texte_fr'=>'Je préfère un environnement de travail prévisible avec peu de risques.', 'source'=>'Schwartz SVS'],
            ['bloc'=>'schwartz', 'dimension'=>'Ach', 'categorie'=>'valeurs', 'texte_fr'=>'Atteindre mes objectifs professionnels et avoir du succès est une priorité.', 'source'=>'Schwartz SVS'],
            ['bloc'=>'schwartz', 'dimension'=>'Ach', 'categorie'=>'valeurs', 'texte_fr'=>'Je suis motivé(e) par les défis qui me permettent de montrer mes capacités.', 'source'=>'Schwartz SVS'],
            ['bloc'=>'schwartz', 'dimension'=>'Ben', 'categorie'=>'valeurs', 'texte_fr'=>'Je veux que mon travail ait un impact positif sur la société ou les autres.', 'source'=>'Schwartz SVS'],
            ['bloc'=>'schwartz', 'dimension'=>'Ben', 'categorie'=>'valeurs', 'texte_fr'=>'Aider les autres de façon désintéressée est plus important que le salaire.', 'source'=>'Schwartz SVS'],
            ['bloc'=>'schwartz', 'dimension'=>'Aut', 'categorie'=>'valeurs', 'texte_fr'=>'J’aime prendre mes propres décisions sans dépendre des autres.', 'source'=>'Schwartz SVS'],
            ['bloc'=>'schwartz', 'dimension'=>'Aut', 'categorie'=>'valeurs', 'texte_fr'=>'Organiser mon travail à ma façon est indispensable à mon épanouissement.', 'source'=>'Schwartz SVS'],
        ];
    }

    /* ══════════════════════════════════════════════════
       PARTIE 6 : BLOCAGES DÉCISIONNELS (5 questions)
    ══════════════════════════════════════════════════ */
    private function cddqQuestions(): array
    {
        return [
            ['bloc'=>'cddq', 'dimension'=>'Manque_Connaissance_Soi', 'categorie'=>'blocage', 'texte_fr'=>'J\'ai du mal à savoir ce que j\'aime vraiment faire comme métier.', 'source'=>'CDDQ'],
            ['bloc'=>'cddq', 'dimension'=>'Manque_Information', 'categorie'=>'blocage', 'texte_fr'=>'Je ne connais pas bien les débouchés des filières qui m\'intéressent.', 'source'=>'CDDQ'],
            ['bloc'=>'cddq', 'dimension'=>'Anxiete_Decisionnelle', 'categorie'=>'blocage', 'texte_fr'=>'L\'idée de devoir choisir mon parcours me stresse beaucoup.', 'source'=>'CDDQ'],
            ['bloc'=>'cddq', 'dimension'=>'Peur_Echec', 'categorie'=>'blocage', 'texte_fr'=>'J\'ai peur de m\'engager dans une filière et d\'échouer.', 'source'=>'CDDQ'],
            ['bloc'=>'cddq', 'dimension'=>'Conflit_Externe', 'categorie'=>'blocage', 'texte_fr'=>'Ce que je veux faire est différent de ce que mes parents/proches attendent de moi.', 'source'=>'CDDQ'],
        ];
    }

    /* ══════════════════════════════════════════════════
       PARTIE 7 : PARCOURS SCOLAIRE & CONTRAINTES (6 questions)
    ══════════════════════════════════════════════════ */
    private function parcoursScolaireQuestions(): array
    {
        return [
            ['bloc'=>'parcours', 'dimension'=>'Scolarite', 'categorie'=>'contraintes', 'texte_fr'=>'J’ai des facilités à mémoriser de grandes quantités d’informations.', 'source'=>'CapAvenir'],
            ['bloc'=>'parcours', 'dimension'=>'Scolarite', 'categorie'=>'contraintes', 'texte_fr'=>'Je suis prêt(e) à faire de longues études (Bac+5 ou plus).', 'source'=>'CapAvenir'],
            ['bloc'=>'parcours', 'dimension'=>'Scolarite', 'categorie'=>'contraintes', 'texte_fr'=>'Je préfère les études courtes, pratiques et professionnalisantes (BTS, Licence Pro).', 'source'=>'CapAvenir'],
            ['bloc'=>'parcours', 'dimension'=>'Contraintes', 'categorie'=>'contraintes', 'texte_fr'=>'Je souhaite étudier près de ma ville d’origine.', 'source'=>'CapAvenir'],
            ['bloc'=>'parcours', 'dimension'=>'Contraintes', 'categorie'=>'contraintes', 'texte_fr'=>'Je suis prêt(e) à étudier à l’étranger si l’opportunité se présente.', 'source'=>'CapAvenir'],
            ['bloc'=>'parcours', 'dimension'=>'Contraintes', 'categorie'=>'contraintes', 'texte_fr'=>'J’envisage de travailler en parallèle de mes études.', 'source'=>'CapAvenir'],
        ];
    }
}
