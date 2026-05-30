<?php

namespace Database\Seeders;

use App\Models\QuestionRiasec;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PsychometricQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }
        QuestionRiasec::truncate();
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $questions = array_merge(
            $this->riasecQuestions(),
            $this->intraDomainQuestions(),
            $this->gatbQuestions(),
            $this->bigFiveQuestions(),
            $this->resilienceQuestions(),
            $this->attentionCheckQuestions(),
            $this->schwartzQuestions(),
            $this->cddqQuestions(),
            $this->parcoursScolaireQuestions(),
            $this->filieresInteretQuestions()
        );

        $ordre = 1;
        foreach ($questions as $q) {
            QuestionRiasec::create(array_merge($q, [
                'ordre'              => $ordre++,
                'actif'              => true,
                'type_reponse'       => $q['type_reponse'] ?? 'likert',
                'poids'              => $q['poids'] ?? 1,
                'discrimination'     => $q['discrimination'] ?? (rand(70, 95) / 10.0),
                'difficulty'         => $q['difficulty'] ?? (rand(-10, 10) / 10.0),
                'is_reverse'         => $q['is_reverse'] ?? false,
                'version'            => '5.0',
                'is_seed'            => true,
                'source'             => $q['source'] ?? 'SIAEPI v5.0',
                'options'            => $q['options'] ?? null,
                'bacs_cibles'        => $q['bacs_cibles'] ?? null,
            ]));
        }

        $this->command->info('✅ ' . QuestionRiasec::count() . ' questions psychométriques v5.0 insérées.');
    }

    /* ══════════════════════════════════════════════════
       PARTIE 1 : INTÉRÊTS RIASEC (30 questions - 20% Reverse)
    ══════════════════════════════════════════════════ */
    private function riasecQuestions(): array
    {
        return [
            // R - Réaliste
            ['bloc'=>'riasec', 'dimension'=>'R', 'categorie'=>'preferences', 'texte_fr'=>'J’aime travailler avec des machines, des outils ou des équipements techniques.'],
            ['bloc'=>'riasec', 'dimension'=>'R', 'categorie'=>'preferences', 'texte_fr'=>'Je préfère les activités physiques et le travail manuel aux tâches de bureau.'],
            ['bloc'=>'riasec', 'dimension'=>'R', 'categorie'=>'preferences', 'texte_fr'=>'J’aime construire, réparer ou assembler des objets.'],
            ['bloc'=>'riasec', 'dimension'=>'R', 'categorie'=>'preferences', 'texte_fr'=>'Je me sens bien en travaillant en extérieur (chantier, nature, agriculture).'],
            ['bloc'=>'riasec', 'dimension'=>'R', 'categorie'=>'preferences', 'texte_fr'=>'Je déteste les travaux manuels et l’utilisation d’outils physiques.', 'is_reverse'=>true],
            
            // I - Investigateur
            ['bloc'=>'riasec', 'dimension'=>'I', 'categorie'=>'preferences', 'texte_fr'=>'J’aime résoudre des problèmes scientifiques ou mathématiques complexes.'],
            ['bloc'=>'riasec', 'dimension'=>'I', 'categorie'=>'preferences', 'texte_fr'=>'Je suis curieux(se) de comprendre comment fonctionnent les systèmes complexes.'],
            ['bloc'=>'riasec', 'dimension'=>'I', 'categorie'=>'preferences', 'texte_fr'=>'J’aime faire des expériences en laboratoire ou analyser des données.'],
            ['bloc'=>'riasec', 'dimension'=>'I', 'categorie'=>'loisirs', 'texte_fr'=>'Je lis des articles scientifiques ou techniques par plaisir.'],
            ['bloc'=>'riasec', 'dimension'=>'I', 'categorie'=>'preferences', 'texte_fr'=>'Je trouve l’analyse de données et la recherche scientifique ennuyeuses.', 'is_reverse'=>true],
            
            // A - Artistique
            ['bloc'=>'riasec', 'dimension'=>'A', 'categorie'=>'preferences', 'texte_fr'=>'J’aime créer des œuvres originales (dessin, musique, écriture, design).'],
            ['bloc'=>'riasec', 'dimension'=>'A', 'categorie'=>'preferences', 'texte_fr'=>'Je préfère un travail créatif sans routine fixe.'],
            ['bloc'=>'riasec', 'dimension'=>'A', 'categorie'=>'preferences', 'texte_fr'=>'J’aime m’exprimer à travers l’art, le théâtre ou les médias.'],
            ['bloc'=>'riasec', 'dimension'=>'A', 'categorie'=>'preferences', 'texte_fr'=>'Je suis très sensible à l’esthétique, aux couleurs et aux formes.'],
            ['bloc'=>'riasec', 'dimension'=>'A', 'categorie'=>'preferences', 'texte_fr'=>'Je suis peu sensible à l’art et à l’esthétique dans mon environnement.', 'is_reverse'=>true],
            
            // S - Social
            ['bloc'=>'riasec', 'dimension'=>'S', 'categorie'=>'preferences', 'texte_fr'=>'J’aime aider, conseiller, enseigner ou soigner les autres.'],
            ['bloc'=>'riasec', 'dimension'=>'S', 'categorie'=>'preferences', 'texte_fr'=>'Je préfère travailler en équipe plutôt que seul(e).'],
            ['bloc'=>'riasec', 'dimension'=>'S', 'categorie'=>'preferences', 'texte_fr'=>'J’aime écouter les autres et les soutenir dans leurs difficultés.'],
            ['bloc'=>'riasec', 'dimension'=>'S', 'categorie'=>'preferences', 'texte_fr'=>'Les métiers qui ont une forte dimension humaine m’attirent beaucoup.'],
            ['bloc'=>'riasec', 'dimension'=>'S', 'categorie'=>'preferences', 'texte_fr'=>'J’évite autant que possible de devoir m’occuper des problèmes des autres.', 'is_reverse'=>true],
            
            // E - Entreprenant
            ['bloc'=>'riasec', 'dimension'=>'E', 'categorie'=>'preferences', 'texte_fr'=>'J’aime convaincre, négocier, vendre ou diriger des projets.'],
            ['bloc'=>'riasec', 'dimension'=>'E', 'categorie'=>'preferences', 'texte_fr'=>'J’ai le goût du risque et des défis ambitieux.'],
            ['bloc'=>'riasec', 'dimension'=>'E', 'categorie'=>'preferences', 'texte_fr'=>'Je suis à l’aise pour parler en public et diriger un groupe.'],
            ['bloc'=>'riasec', 'dimension'=>'E', 'categorie'=>'preferences', 'texte_fr'=>'J’aime la compétition et la réussite sociale.'],
            ['bloc'=>'riasec', 'dimension'=>'E', 'categorie'=>'preferences', 'texte_fr'=>'Je préfère suivre les directives plutôt que d’avoir à diriger les autres.', 'is_reverse'=>true],
            
            // C - Conventionnel
            ['bloc'=>'riasec', 'dimension'=>'C', 'categorie'=>'preferences', 'texte_fr'=>'J’aime classer, organiser des informations et travailler avec des données.'],
            ['bloc'=>'riasec', 'dimension'=>'C', 'categorie'=>'preferences', 'texte_fr'=>'Je préfère suivre des procédures précises et des règles claires.'],
            ['bloc'=>'riasec', 'dimension'=>'C', 'categorie'=>'preferences', 'texte_fr'=>'Je me sens bien en travaillant avec des chiffres et des tableaux.'],
            ['bloc'=>'riasec', 'dimension'=>'C', 'categorie'=>'preferences', 'texte_fr'=>'Je suis méthodique, rigoureux(se) et attentif(ve) aux détails.'],
            ['bloc'=>'riasec', 'dimension'=>'C', 'categorie'=>'preferences', 'texte_fr'=>'Je dÉteste travailler avec des chiffres, des tableaux ou des classements.', 'is_reverse'=>true],
        ];
    }

    /* ══════════════════════════════════════════════════
       PARTIE 2 : DISCRIMINATION INTRA-DOMAINE (10 questions)
    ══════════════════════════════════════════════════ */
    private function intraDomainQuestions(): array
    {
        return [
            // Santé
            ['bloc'=>'intra', 'dimension'=>'SANTE', 'categorie'=>'intra', 'texte_fr'=>'Je veux diagnostiquer des maladies et prescrire des traitements.'],
            ['bloc'=>'intra', 'dimension'=>'SANTE', 'categorie'=>'intra', 'texte_fr'=>'Je suis passionné(e) par les médicaments et leurs effets.'],
            ['bloc'=>'intra', 'dimension'=>'SANTE', 'categorie'=>'intra', 'texte_fr'=>'Je suis attiré(e) par la chirurgie et les soins complexes.'],
            ['bloc'=>'intra', 'dimension'=>'SANTE', 'categorie'=>'intra', 'texte_fr'=>'Je veux aider les patients par la rééducation et la kinésithérapie.'],
            
            // Tech / Info
            ['bloc'=>'intra', 'dimension'=>'INFO', 'categorie'=>'intra', 'texte_fr'=>'J’aime programmer, coder et résoudre des problèmes algorithmiques.'],
            ['bloc'=>'intra', 'dimension'=>'INFO', 'categorie'=>'intra', 'texte_fr'=>'Je suis attiré(e) par l’intelligence artificielle et la cybersécurité.'],
            ['bloc'=>'intra', 'dimension'=>'ING', 'categorie'=>'intra', 'texte_fr'=>'Je veux concevoir des systèmes industriels ou des réseaux techniques.'],
            
            // Droit / Eco
            ['bloc'=>'intra', 'dimension'=>'DROIT', 'categorie'=>'intra', 'texte_fr'=>'Je suis intéressé(e) par les lois, la justice et la défense des droits.'],
            ['bloc'=>'intra', 'dimension'=>'ECO', 'categorie'=>'intra', 'texte_fr'=>'L’univers de la finance, de la bourse et de l’économie m’intéresse.'],
            ['bloc'=>'intra', 'dimension'=>'MGT', 'categorie'=>'intra', 'texte_fr'=>'J’aimerais gérer une entreprise et ses ressources humaines.'],
        ];
    }

    /* ══════════════════════════════════════════════════
       PARTIE 3 : APTITUDES GATB (Objectif - 12 questions)
    ══════════════════════════════════════════════════ */
    private function gatbQuestions(): array
    {
        return [
            // G - Général (Raisonnement Logique)
            ['bloc'=>'gatb', 'dimension'=>'GATB_G', 'categorie'=>'aptitudes', 'type_reponse'=>'choice', 
             'texte_fr'=>'Quelle est la suite logique : 2 - 4 - 8 - 16 - ?', 
             'options'=>[['valeur'=>1, 'label'=>'20'], ['valeur'=>1, 'label'=>'24'], ['valeur'=>5, 'label'=>'32'], ['valeur'=>1, 'label'=>'40']]],
            ['bloc'=>'gatb', 'dimension'=>'GATB_G', 'categorie'=>'aptitudes', 'type_reponse'=>'choice', 
             'texte_fr'=>'Si certains A sont B, et tous les B sont C, alors :', 
             'options'=>[['valeur'=>5, 'label'=>'Certains A sont C'], ['valeur'=>1, 'label'=>'Tous les A sont C'], ['valeur'=>1, 'label'=>'Aucun A n\'est C']]],
            ['bloc'=>'gatb', 'dimension'=>'GATB_G', 'categorie'=>'aptitudes', 'type_reponse'=>'choice', 
             'texte_fr'=>'Trouvez l\'intrus parmi ces mots :', 
             'options'=>[['valeur'=>1, 'label'=>'Cercle'], ['valeur'=>1, 'label'=>'Carré'], ['valeur'=>5, 'label'=>'Pyramide'], ['valeur'=>1, 'label'=>'Triangle']]],

            // V - Verbal (Compréhension & Lexique)
            ['bloc'=>'gatb', 'dimension'=>'GATB_V', 'categorie'=>'aptitudes', 'type_reponse'=>'choice', 
             'texte_fr'=>'Quel est le synonyme le plus proche de "PRAGMATIQUE" ?', 
             'options'=>[['valeur'=>1, 'label'=>'Théorique'], ['valeur'=>5, 'label'=>'Pratique'], ['valeur'=>1, 'label'=>'Rêveur']]],
            ['bloc'=>'gatb', 'dimension'=>'GATB_V', 'categorie'=>'aptitudes', 'type_reponse'=>'choice', 
             'texte_fr'=>'Complétez l\'analogie : AVION est à AIR ce que BATEAU est à :', 
             'options'=>[['valeur'=>1, 'label'=>'Voile'], ['valeur'=>1, 'label'=>'Port'], ['valeur'=>5, 'label'=>'Eau']]],
            ['bloc'=>'gatb', 'dimension'=>'GATB_V', 'categorie'=>'aptitudes', 'type_reponse'=>'choice', 
             'texte_fr'=>'Que signifie le mot "ÉPHÉMÈRE" ?', 
             'options'=>[['valeur'=>1, 'label'=>'Éternel'], ['valeur'=>5, 'label'=>'Qui dure peu'], ['valeur'=>1, 'label'=>'Brillant']]],

            // N - Numérique (Calcul & Probabilités)
            ['bloc'=>'gatb', 'dimension'=>'GATB_N', 'categorie'=>'aptitudes', 'type_reponse'=>'choice', 
             'texte_fr'=>'Si 3 ouvriers construisent un mur en 6 heures, combien d\'heures faut-il à 6 ouvriers ?', 
             'options'=>[['valeur'=>1, 'label'=>'12h'], ['valeur'=>5, 'label'=>'3h'], ['valeur'=>1, 'label'=>'2h']]],
            ['bloc'=>'gatb', 'dimension'=>'GATB_N', 'categorie'=>'aptitudes', 'type_reponse'=>'choice', 
             'texte_fr'=>'Combien font 15% de 200 ?', 
             'options'=>[['valeur'=>1, 'label'=>'15'], ['valeur'=>1, 'label'=>'20'], ['valeur'=>5, 'label'=>'30']]],
            ['bloc'=>'gatb', 'dimension'=>'GATB_N', 'categorie'=>'aptitudes', 'type_reponse'=>'choice', 
             'texte_fr'=>'Quelle est la probabilité de tirer un "6" avec un dé classique ?', 
             'options'=>[['valeur'=>1, 'label'=>'1/2'], ['valeur'=>5, 'label'=>'1/6'], ['valeur'=>1, 'label'=>'1/10']]],

            // S - Spatial (Visualisation)
            ['bloc'=>'gatb', 'dimension'=>'GATB_S', 'categorie'=>'aptitudes', 'type_reponse'=>'choice', 
             'texte_fr'=>'Si vous faites pivoter un "L" de 180° vers la droite, à quoi ressemble-t-il ?', 
             'options'=>[['valeur'=>1, 'label'=>'Un L normal'], ['valeur'=>1, 'label'=>'Un L couché'], ['valeur'=>5, 'label'=>'Un L à l\'envers et retourné']]],
            ['bloc'=>'gatb', 'dimension'=>'GATB_S', 'categorie'=>'aptitudes', 'type_reponse'=>'choice', 
             'texte_fr'=>'Une boîte a 6 faces. Si on l\'ouvre à plat, combien de carrés verra-t-on ?', 
             'options'=>[['valeur'=>1, 'label'=>'4'], ['valeur'=>5, 'label'=>'6'], ['valeur'=>1, 'label'=>'8']]],
            ['bloc'=>'gatb', 'dimension'=>'GATB_S', 'categorie'=>'aptitudes', 'type_reponse'=>'choice', 
             'texte_fr'=>'Quel objet est le plus proche d\'un cylindre ?', 
             'options'=>[['valeur'=>1, 'label'=>'Un ballon'], ['valeur'=>5, 'label'=>'Une canette'], ['valeur'=>1, 'label'=>'Une boîte de pizza']]],
        ];
    }

    /* ══════════════════════════════════════════════════
       PARTIE 4 : PERSONNALITÉ BIG FIVE (14 questions)
    ══════════════════════════════════════════════════ */
    private function bigFiveQuestions(): array
    {
        return [
            // Ouverture
            ['bloc'=>'big_five', 'dimension'=>'O', 'categorie'=>'personnalite', 'texte_fr'=>'Je suis curieux(se) et j’aime découvrir de nouvelles choses.'],
            ['bloc'=>'big_five', 'dimension'=>'O', 'categorie'=>'personnalite', 'texte_fr'=>'J’aime explorer des idées nouvelles et inhabituelles.'],
            ['bloc'=>'big_five', 'dimension'=>'O', 'categorie'=>'personnalite', 'texte_fr'=>'Je préfère la routine et les choses familières au changement.', 'is_reverse'=>true],
            
            // Conscienciosité
            ['bloc'=>'big_five', 'dimension'=>'C', 'categorie'=>'personnalite', 'texte_fr'=>'Je suis organisé(e) et je termine toujours ce que je commence.'],
            ['bloc'=>'big_five', 'dimension'=>'C', 'categorie'=>'personnalite', 'texte_fr'=>'Je suis discipliné(e) dans mon travail.'],
            ['bloc'=>'big_five', 'dimension'=>'C', 'categorie'=>'personnalite', 'texte_fr'=>'Je procrastine souvent avant de terminer mes tâches.', 'is_reverse'=>true],
            
            // Extraversion
            ['bloc'=>'big_five', 'dimension'=>'E', 'categorie'=>'personnalite', 'texte_fr'=>'Je me sens à l’aise en public et je prends facilement la parole.'],
            ['bloc'=>'big_five', 'dimension'=>'E', 'categorie'=>'personnalite', 'texte_fr'=>'Je suis énergique et sociable.'],
            ['bloc'=>'big_five', 'dimension'=>'E', 'categorie'=>'personnalite', 'texte_fr'=>'Je préfère passer mes soirées seul(e) au calme.', 'is_reverse'=>true],
            
            // Agréabilité
            ['bloc'=>'big_five', 'dimension'=>'A', 'categorie'=>'personnalite', 'texte_fr'=>'Je suis sensible aux émotions des autres.'],
            ['bloc'=>'big_five', 'dimension'=>'A', 'categorie'=>'personnalite', 'texte_fr'=>'Je fais facilement confiance aux gens.'],
            ['bloc'=>'big_five', 'dimension'=>'A', 'categorie'=>'personnalite', 'texte_fr'=>'Je préfère m’imposer plutôt que de coopérer.', 'is_reverse'=>true],
            
            // Névrosisme / Stabilité
            ['bloc'=>'big_five', 'dimension'=>'N', 'categorie'=>'personnalite', 'texte_fr'=>'Je gère bien le stress et reste calme sous pression.', 'is_reverse'=>true],
            ['bloc'=>'big_five', 'dimension'=>'N', 'categorie'=>'personnalite', 'texte_fr'=>'Je m’inquiète souvent pour des choses sans importance.'],
        ];
    }

    /* ══════════════════════════════════════════════════
       PARTIE 5 : RÉSILIENCE & PERSÉVÉRANCE (Nouveau Bloc)
    ══════════════════════════════════════════════════ */
    private function resilienceQuestions(): array
    {
        return [
            ['bloc'=>'resilience', 'dimension'=>'RESILIENCE', 'categorie'=>'personnalite', 'texte_fr'=>'Je n’abandonne jamais mes objectifs, même face à des obstacles majeurs.'],
            ['bloc'=>'resilience', 'dimension'=>'RESILIENCE', 'categorie'=>'personnalite', 'texte_fr'=>'Je me remets rapidement après un échec important.'],
            ['bloc'=>'resilience', 'dimension'=>'RESILIENCE', 'categorie'=>'personnalite', 'texte_fr'=>'Je suis capable de rester concentré sur une tâche difficile pendant des heures.'],
            ['bloc'=>'resilience', 'dimension'=>'RESILIENCE', 'categorie'=>'personnalite', 'texte_fr'=>'Je gère bien la pression lors des examens cruciaux.'],
            ['bloc'=>'resilience', 'dimension'=>'RESILIENCE', 'categorie'=>'personnalite', 'texte_fr'=>'Je me sens souvent découragé quand les choses ne se passent pas comme prévu.', 'is_reverse'=>true],
            ['bloc'=>'resilience', 'dimension'=>'RESILIENCE', 'categorie'=>'personnalite', 'texte_fr'=>'Je préfère éviter les situations complexes pour ne pas risquer l\'échec.', 'is_reverse'=>true],
        ];
    }

    /* ══════════════════════════════════════════════════
       PARTIE 6 : ATTENTION CHECKS (Trap questions)
    ══════════════════════════════════════════════════ */
    private function attentionCheckQuestions(): array
    {
        return [
            ['bloc'=>'attention', 'dimension'=>'ATTENTION', 'categorie'=>'validation', 'type_reponse'=>'choice', 
             'texte_fr'=>'Pour vérifier votre attention, veuillez sélectionner "Tout à fait" ci-dessous.', 
             'options'=>[['valeur'=>1, 'label'=>'Pas du tout'], ['valeur'=>1, 'label'=>'Neutre'], ['valeur'=>5, 'label'=>'Tout à fait']]],
            ['bloc'=>'attention', 'dimension'=>'ATTENTION', 'categorie'=>'validation', 'type_reponse'=>'choice', 
             'texte_fr'=>'Ignorez cette question et sélectionnez le chiffre "3".', 
             'options'=>[['valeur'=>1, 'label'=>'1'], ['valeur'=>1, 'label'=>'2'], ['valeur'=>5, 'label'=>'3']]],
        ];
    }

    private function schwartzQuestions(): array { return []; }
    private function cddqQuestions(): array { return []; }
    private function parcoursScolaireQuestions(): array { return []; }
    private function filieresInteretQuestions(): array { return []; }
}
