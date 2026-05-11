<?php

namespace Database\Seeders;

use App\Models\QuestionRiasec;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Insere la banque ORIENTIA complete : 90 questions RIASEC
 * (15 questions par dimension Holland).
 *
 * L'ordre est entrelace R-I-A-S-E-C pour que les 18 premieres questions
 * couvrent exactement 3 questions par type.
 */
class QuestionRiasecSeeder extends Seeder
{
    private const DIMENSION_ORDER = ['R', 'I', 'A', 'S', 'E', 'C'];

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        QuestionRiasec::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($this->questions() as $question) {
            QuestionRiasec::create($question);
        }

        $this->command->info('90 questions ORIENTIA RIASEC inserees (15 par dimension).');
    }

    private function questions(): array
    {
        $bank = [
            'R' => [
                'Planter, entretenir des arbres, des arbustes, des fleurs ou cultiver le sol',
                'Faire de la couture',
                'Effectuer des reparations electriques ou electroniques',
                'Reparer des automobiles',
                'Fabriquer des objets avec du bois',
                'Conduire un camion ou un tracteur',
                'Faire des demenagements',
                'Entretenir ou reparer de la plomberie ou des systemes de ventilation',
                'Travailler avec des animaux et en prendre soin',
                'Travailler a l\'exterieur, expose a la pluie, au soleil ou au froid',
                'Travailler avec de la machinerie',
                'Preparer des repas ou des banquets',
                'Travailler avec des outils tels que tournevis, ciseaux ou pince',
                'Effectuer des travaux de peinture',
                'Suivre un cours de dessin mecanique',
            ],
            'I' => [
                'Lire des revues scientifiques ou specialisees',
                'Faire des experiences sur le comportement des animaux',
                'Effectuer des analyses en laboratoire',
                'Suivre des cours de science',
                'Etablir un diagnostic de maladie chez des patients',
                'Experimenter de nouvelles cultures vegetales',
                'Creer une nouvelle source d\'energie',
                'Concevoir un barrage electrique, un pont ou une autoroute',
                'Resoudre des problemes mathematiques',
                'Faire des recherches scientifiques pour satisfaire ma curiosite',
                'Produire un rapport sur l\'evolution economique d\'une region',
                'Concevoir un plan de reboisement',
                'Planifier des soins ou des traitements a donner a des patients',
                'Faire des recherches sur les civilisations anciennes',
                'Faire de la programmation informatique',
            ],
            'A' => [
                'Ecrire des romans ou des articles de journaux',
                'Dessiner des meubles, des plans de maison ou des decors',
                'Interpreter ou mettre en scene une piece de theatre',
                'Jouer dans un orchestre',
                'Creer des pieces d\'artisanat',
                'Imaginer de nouvelles facons de faire les choses',
                'Embellir l\'environnement',
                'Creer une nouvelle coupe de cheveux ou de nouveaux vetements',
                'Faire de la photo publicitaire ou artistique',
                'Concevoir et realiser des arrangements de fleurs',
                'Imaginer une bande dessinee',
                'Peindre des tableaux pour une exposition',
                'Decorer des vitrines de magasins',
                'Ecrire des critiques de pieces de theatre, de films ou de livres',
                'Apprendre des langues',
            ],
            'S' => [
                'S\'engager dans des organismes sociaux ou communautaires',
                'Rencontrer des gens pour les aider a resoudre leurs problemes personnels',
                'Travailler dans une garderie d\'enfants',
                'Enseigner aux enfants, aux adolescents ou aux adultes',
                'Aider les gens a prendre conscience de leurs possibilites',
                'Animer un groupe',
                'Agir comme hote ou hotesse lors d\'un congres',
                'Tenir un kiosque d\'information',
                'Offrir un service d\'aide telephonique',
                'Consulter les gens sur leurs besoins, par sondage ou enquete',
                'S\'occuper des personnes handicapees physiquement',
                'Soigner un malade',
                'Aider les delinquants a se rehabiliter',
                'Organiser des loisirs',
                'Echanger des idees avec d\'autres personnes',
            ],
            'E' => [
                'Vendre un produit ou une idee',
                'Mettre sur pied son propre commerce',
                'Donner des conferences',
                'Acheter pour le compte d\'un grand magasin',
                'Marchander',
                'Diriger du personnel',
                'Defendre une cause',
                'Faire de la selection de candidats',
                'S\'engager a fond dans des activites sociales au travail ou dans son quartier',
                'Diriger une entreprise financiere',
                'Lancer un nouveau produit',
                'Fonder un club de consommateurs, une association de protection de l\'environnement ou une activite de recuperation de papier',
                'Gerer un projet special',
                'Faire de la politique municipale, scolaire ou provinciale',
                'Lancer un concours de promotion ou une campagne de publicite',
            ],
            'C' => [
                'Etablir des comptes-rendus de depenses',
                'Preparer des proces-verbaux de reunions',
                'Tenir une caisse dans un etablissement commercial ou financier',
                'Faire un inventaire ou passer des commandes',
                'Manipuler un appareil a traitement de textes',
                'Maintenir un systeme de classement, en archive, bibliotheque ou bureau',
                'Garder mon espace de travail en ordre',
                'Faire des operations mathematiques pour preparer des rapports ou faire de la tenue de livres',
                'S\'adonner a des activites regulieres',
                'Classer des lettres, des rapports ou des dossiers',
                'Recevoir et passer des appels telephoniques',
                'Verifier des rapports financiers',
                'Faire fonctionner toutes sortes de machines de bureau',
                'Effectuer des taches clairement definies',
                'Dactylographier ou reviser des lettres ou des rapports',
            ],
        ];

        $questions = [];

        for ($itemIndex = 1; $itemIndex <= 15; $itemIndex++) {
            foreach (self::DIMENSION_ORDER as $dimPosition => $dimension) {
                // Map dimensions to preferred Bac types
                $bacsCibles = match ($dimension) {
                    'R' => ['Sciences Expérimentales', 'Sciences Techniques', 'Sport'],
                    'I' => ['Mathématiques', 'Sciences de l\'Informatique', 'Sciences Expérimentales'],
                    'A' => ['Lettres', 'Économie et Gestion'],
                    'S' => ['Lettres', 'Économie et Gestion', 'Sciences Expérimentales'],
                    'E' => ['Économie et Gestion', 'Lettres', 'Sciences de l\'Informatique'],
                    'C' => ['Économie et Gestion', 'Mathématiques', 'Sciences de l\'Informatique'],
                    default => [],
                };

                $questions[] = [
                    'dimension' => $dimension,
                    'categorie' => $this->categoryFor($itemIndex),
                    'ordre' => (($itemIndex - 1) * count(self::DIMENSION_ORDER)) + $dimPosition + 1,
                    'texte_fr' => $bank[$dimension][$itemIndex - 1],
                    'texte_ar' => null,
                    'type_reponse' => 'likert',
                    'options' => null,
                    'poids' => 1,
                    'actif' => true,
                    'source' => 'ORIENTIA RIASEC 90',
                    'difficulty' => $this->difficultyFor($itemIndex),
                    'discrimination' => $itemIndex <= 3 ? 1.20 : 1.00,
                    'is_reverse' => false,
                    'calibration_version' => 'v1.0',
                    'is_seed' => $itemIndex <= 3,
                    'version' => '2.0',
                    'bacs_cibles' => json_encode($bacsCibles),
                ];
            }
        }

        return $questions;
    }

    private function categoryFor(int $itemIndex): string
    {
        return match (true) {
            $itemIndex <= 5 => 'loisirs',
            $itemIndex <= 10 => 'preferences_professionnelles',
            default => 'qualites_personnelles',
        };
    }

    private function difficultyFor(int $itemIndex): float
    {
        return round(2.40 + ((($itemIndex - 1) % 5) * 0.30), 2);
    }
}
