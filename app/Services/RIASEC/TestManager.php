<?php

namespace App\Services\RIASEC;

use App\Models\AnswerRiasec;
use App\Models\ProfileRiasec;
use App\Models\QuestionRiasec;
use App\Services\RIASEC\DTO\RiasecScoreDTO;
use App\Services\RIASEC\DTO\TestProgressDTO;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * TestManager — Service central du module RIASEC.
 *
 * Orchestre l'intégralité du cycle de vie d'un test Holland :
 *   1. Chargement des questions
 *   2. Enregistrement des réponses (auth + invité)
 *   3. Calcul des scores bruts et normalisés
 *   4. Détermination du trigramme dominant avec départage intelligent
 *   5. Vérification de la cohérence interne (questions inversées)
 *   6. Génération de l'interprétation textuelle sans IA
 *   7. Gestion de la progression
 *
 * Compatible : utilisateurs authentifiés ET invités (session PHP).
 * SOLID : chaque responsabilité est isolée dans une méthode dédiée.
 */
class TestManager
{
    // ── Priorité Holland pour le départage en cas d'égalité ────────────────
    private const HOLLAND_PRIORITY = ['R' => 0, 'I' => 1, 'A' => 2, 'S' => 3, 'E' => 4, 'C' => 5];

    // ── Valeurs min/max de l'échelle Likert ───────────────────────────────
    private const LIKERT_MIN = 1;
    private const LIKERT_MAX = 5;

    // ── TTL du cache questions (en secondes) ──────────────────────────────
    private const CACHE_QUESTIONS_TTL = 3600;

    // ── Questions inversées : leur score est renversé (6 - valeur) ────────
    // Permet de détecter l'incohérence des répondants (ex: "je n'aime pas du tout X"
    // alors qu'une question inversée de même dimension a obtenu 5).
    // Renseigner ici les IDs ou codes de questions à inverser après import.
    private const INVERTED_QUESTION_CODES = [
        // Exemple : 'R_Q7', 'I_Q15' — à alimenter selon votre banque
        // La logique s'applique automatiquement si le code est dans cette liste.
    ];

    // ── Libellés d'interprétation par dimension ───────────────────────────
    private const DIMENSION_PROFILES = [
        'R' => [
            'label'       => 'Réaliste',
            'emoji'       => '🔧',
            'description' => 'Tu as un profil pratique et concret. Tu aimes travailler avec tes mains, manipuler des outils et voir des résultats tangibles. Tu es fiable, persistant(e) et tu préfères l\'action à la théorie.',
            'forces'      => ['Dextérité manuelle', 'Sens pratique', 'Endurance physique', 'Fiabilité'],
            'metiers'     => ['Ingénieur civil', 'Technicien de maintenance', 'Architecte', 'Pilote', 'Chirurgien'],
            'filieres'    => ['Génie civil', 'Génie mécanique', 'Génie électrique', 'Agriculture'],
        ],
        'I' => [
            'label'       => 'Investigateur',
            'emoji'       => '🔬',
            'description' => 'Tu as un profil analytique et scientifique. Tu aimes comprendre le monde, analyser des données complexes et résoudre des problèmes intellectuels. Curieux(se) et rigoureux(se), tu te nourris de connaissances.',
            'forces'      => ['Pensée analytique', 'Curiosité intellectuelle', 'Rigueur scientifique', 'Autonomie'],
            'metiers'     => ['Chercheur', 'Médecin', 'Data Scientist', 'Ingénieur R&D', 'Pharmacien'],
            'filieres'    => ['Médecine', 'Informatique', 'Physique', 'Mathématiques', 'Biologie'],
        ],
        'A' => [
            'label'       => 'Artistique',
            'emoji'       => '🎨',
            'description' => 'Tu as un profil créatif et expressif. Tu vois le monde autrement, tu aimes inventer, innover et exprimer tes idées. Sensible à l\'esthétique, tu t\'épanouis dans des environnements qui valorisent l\'originalité.',
            'forces'      => ['Créativité', 'Sens esthétique', 'Expression personnelle', 'Originalité'],
            'metiers'     => ['Architecte', 'Graphiste', 'Journaliste', 'Designer UX', 'Réalisateur'],
            'filieres'    => ['Architecture', 'Design', 'Communication', 'Arts', 'Lettres'],
        ],
        'S' => [
            'label'       => 'Social',
            'emoji'       => '🤝',
            'description' => 'Tu as un profil humain et altruiste. Tu aimes aider, enseigner, conseiller et travailler avec les autres. Empathique et communicant(e), tu trouves ton sens dans les relations et l\'impact sur les personnes.',
            'forces'      => ['Empathie', 'Communication', 'Travail en équipe', 'Sens du service'],
            'metiers'     => ['Médecin', 'Enseignant', 'Psychologue', 'Assistante sociale', 'RH'],
            'filieres'    => ['Sciences de l\'éducation', 'Psychologie', 'Médecine', 'Droit', 'Sociologie'],
        ],
        'E' => [
            'label'       => 'Entreprenant',
            'emoji'       => '🚀',
            'description' => 'Tu as un profil leader et ambitieux. Tu aimes convaincre, diriger, prendre des risques calculés et influencer les décisions. Dynamique et charismatique, tu te projettes naturellement dans des rôles de responsabilité.',
            'forces'      => ['Leadership', 'Persuasion', 'Prise de décision', 'Ambition'],
            'metiers'     => ['Chef de projet', 'Entrepreneur', 'Avocat', 'Commercial', 'Directeur'],
            'filieres'    => ['Management', 'Droit', 'Commerce', 'Marketing', 'Économie'],
        ],
        'C' => [
            'label'       => 'Conventionnel',
            'emoji'       => '📋',
            'description' => 'Tu as un profil organisé et méthodique. Tu aimes la structure, les procédures claires et les environnements ordonnés. Précis(e), fiable et efficace, tu excelles dans la gestion de données et l\'organisation.',
            'forces'      => ['Organisation', 'Précision', 'Rigueur', 'Fiabilité'],
            'metiers'     => ['Comptable', 'Contrôleur de gestion', 'Notaire', 'Statisticien', 'Auditeur'],
            'filieres'    => ['Comptabilité', 'Finance', 'Informatique de gestion', 'Administration', 'Droit'],
        ],
    ];

    // ══════════════════════════════════════════════════════════════════════
    // 1. CHARGEMENT DES QUESTIONS
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Retourne les questions actives groupées par catégorie, dans l'ordre configuré.
     * Résultats mis en cache pour éviter les requêtes répétitives pendant le test.
     *
     * @return Collection<string, Collection<int, QuestionRiasec>>
     *         Exemple : ['loisirs' => [...], 'preferences_professionnelles' => [...], ...]
     */
    public function getQuestions(): Collection
    {
        return Cache::remember('riasec.questions.grouped', self::CACHE_QUESTIONS_TTL, function () {
            return QuestionRiasec::actives()
                ->ordonnes()
                ->get()
                ->groupBy('categorie');
        });
    }

    /**
     * Retourne toutes les questions actives dans un tableau plat ordonné.
     * Utilisé pour afficher le test question par question.
     *
     * @return Collection<int, QuestionRiasec>
     */
    public function getAllQuestions(): Collection
    {
        return Cache::remember('riasec.questions.flat', self::CACHE_QUESTIONS_TTL, function () {
            return QuestionRiasec::actives()->ordonnes()->get();
        });
    }

    /**
     * Retourne la prochaine question sans réponse pour une session donnée.
     */
    public function getNextQuestion(?int $userId, string $sessionId): ?QuestionRiasec
    {
        $answeredIds = $this->getAnsweredQuestionIds($userId, $sessionId);

        return QuestionRiasec::actives()
            ->ordonnes()
            ->whereNotIn('id', $answeredIds)
            ->first();
    }

    // ══════════════════════════════════════════════════════════════════════
    // 2. ENREGISTREMENT DES RÉPONSES
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Enregistre ou met à jour la réponse d'un utilisateur/invité à une question.
     *
     * @param int|null $userId     Null si utilisateur invité
     * @param int      $questionId
     * @param int      $score      Valeur Likert (1-5) ou booléen (0/1)
     * @param string   $sessionId  UUID de la session de test
     * @param string|null $guestId ID de session PHP (pour les invités)
     * @param int|null $tempsMs    Temps de réponse en millisecondes
     *
     * @throws \InvalidArgumentException Si le score est hors plage valide
     */
    public function saveAnswer(
        ?int    $userId,
        int     $questionId,
        int     $score,
        string  $sessionId,
        ?string $guestId = null,
        ?int    $tempsMs = null
    ): AnswerRiasec {
        // Validation du score
        $question = QuestionRiasec::findOrFail($questionId);

        if (! $question->valeurEstValide($score)) {
            throw new \InvalidArgumentException(
                "Score {$score} invalide pour la question #{$questionId} (type: {$question->type_reponse})."
            );
        }

        // Invalidation du cache de scores pour cette session
        Cache::forget("riasec.scores.{$sessionId}");

        return AnswerRiasec::enregistrer(
            sessionId:  $sessionId,
            questionId: $questionId,
            valeur:     $score,
            userId:     $userId,
            guestId:    $guestId,
            tempsMs:    $tempsMs
        );
    }

    // ══════════════════════════════════════════════════════════════════════
    // 3. CALCUL DES SCORES
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Calcule les scores RIASEC bruts et normalisés pour une session donnée.
     *
     * Algorithme :
     *  1. Charge toutes les réponses avec leurs questions (eager load, pas de N+1)
     *  2. Applique l'inversion des questions marquées comme inversées
     *  3. Somme les scores pondérés par dimension
     *  4. Normalise sur 100 (score brut / score max théorique × 100)
     *  5. Calcule le score de cohérence interne
     *
     * @return RiasecScoreDTO
     */
    public function calculateScores(?int $userId, string $sessionId): RiasecScoreDTO
    {
        return Cache::remember("riasec.scores.{$sessionId}", 300, function () use ($userId, $sessionId) {
            // Charge les réponses avec questions en un seul requête
            $answers = AnswerRiasec::session($sessionId)
                ->avecQuestion()
                ->get();

            $dimensions   = array_keys(QuestionRiasec::DIMENSIONS);
            $rawScores    = array_fill_keys($dimensions, 0);
            $maxScores    = array_fill_keys($dimensions, 0);
            $countByDim   = array_fill_keys($dimensions, 0);

            foreach ($answers as $answer) {
                $question = $answer->question;
                if (! $question || ! isset($rawScores[$question->dimension])) {
                    continue;
                }

                $dim   = $question->dimension;
                $poids = $question->poids;

                // Inversion des questions de contrôle
                $valeur = $this->isInvertedQuestion($question)
                    ? $this->invertScore($answer->valeur, $question->type_reponse)
                    : $answer->valeur;

                $rawScores[$dim]  += $valeur * $poids;
                $maxScores[$dim]  += self::LIKERT_MAX * $poids;
                $countByDim[$dim] += 1;
            }

            // Normalisation 0–100
            $normalized = [];
            foreach ($dimensions as $dim) {
                $normalized[$dim] = $maxScores[$dim] > 0
                    ? round(($rawScores[$dim] / $maxScores[$dim]) * 100, 2)
                    : 0.0;
            }

            $trigram    = $this->determineDominantTrigram($normalized);
            $coherence  = $this->calculateCoherenceScore($answers, $countByDim);

            return new RiasecScoreDTO(
                rawScores:        $rawScores,
                normalizedScores: $normalized,
                trigram:          $trigram,
                totalAnswers:     $answers->count(),
                coherenceScore:   $coherence,
            );
        });
    }

    // ══════════════════════════════════════════════════════════════════════
    // 4. TRIGRAMME DOMINANT
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Détermine le code Holland à 3 lettres dominant.
     *
     * Règles de classement :
     *  1. Score normalisé décroissant (plus haut = premier)
     *  2. En cas d'égalité parfaite : priorité Holland R > I > A > S > E > C
     *
     * @param  array<string, float> $normalizedScores  ['R'=>72.5, 'I'=>80.0, ...]
     * @return string  Trigramme (ex: "IRA")
     */
    public function determineDominantTrigram(array $normalizedScores): string
    {
        // Copie pour ne pas modifier l'original
        $scores = $normalizedScores;

        // Tri stable : score décroissant, puis priorité Holland croissante
        uksort($scores, function (string $a, string $b) use ($scores): int {
            $scoreDiff = $scores[$b] <=> $scores[$a];
            if ($scoreDiff !== 0) {
                return $scoreDiff;
            }
            // Égalité → priorité Holland (R=0, I=1, ..., C=5)
            return self::HOLLAND_PRIORITY[$a] <=> self::HOLLAND_PRIORITY[$b];
        });

        return implode('', array_slice(array_keys($scores), 0, 3));
    }

    // ══════════════════════════════════════════════════════════════════════
    // 5. COMPLÉTION DU TEST
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Vérifie si toutes les questions actives ont reçu une réponse dans la session.
     */
    public function isTestCompleted(?int $userId, string $sessionId): bool
    {
        $totalActive  = QuestionRiasec::actives()->count();
        $totalAnswered = AnswerRiasec::session($sessionId)->count();

        return $totalAnswered >= $totalActive;
    }

    // ══════════════════════════════════════════════════════════════════════
    // 6. PROGRESSION
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Retourne un DTO complet de progression du test.
     *
     * @return TestProgressDTO
     */
    public function getProgress(?int $userId, string $sessionId): TestProgressDTO
    {
        $allQuestions = $this->getAllQuestions();
        $total        = $allQuestions->count();

        // Questions répondues pour cette session (indexées par question_id)
        $answered = AnswerRiasec::session($sessionId)
            ->pluck('question_id')
            ->flip(); // flip pour O(1) lookup

        $answeredByDim  = array_fill_keys(array_keys(QuestionRiasec::DIMENSIONS), 0);
        $remainingByDim = array_fill_keys(array_keys(QuestionRiasec::DIMENSIONS), 0);
        $lastQuestionId = null;

        foreach ($allQuestions as $q) {
            if ($answered->has($q->id)) {
                $answeredByDim[$q->dimension]++;
                $lastQuestionId = $q->id;
            } else {
                $remainingByDim[$q->dimension]++;
            }
        }

        $answeredCount = array_sum($answeredByDim);
        $percentage    = $total > 0 ? ($answeredCount / $total) * 100 : 0.0;

        return new TestProgressDTO(
            answered:             $answeredCount,
            total:                $total,
            percentage:           $percentage,
            isCompleted:          $answeredCount >= $total,
            answeredByDimension:  $answeredByDim,
            remainingByDimension: $remainingByDim,
            lastQuestionId:       $lastQuestionId,
            sessionId:            $sessionId,
        );
    }

    // ══════════════════════════════════════════════════════════════════════
    // 7. GESTION DU PROFIL
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Crée ou met à jour le ProfileRiasec après calcul des scores.
     * À appeler en fin de test ou après chaque réponse (mode temps-réel).
     */
    public function saveProfile(?int $userId, string $sessionId, ?string $guestId = null): ProfileRiasec
    {
        $scores   = $this->calculateScores($userId, $sessionId);
        $progress = $this->getProgress($userId, $sessionId);

        $interp   = $this->generateInterpretation($scores);

        return ProfileRiasec::updateOrCreate(
            ['test_session_id' => $sessionId],
            [
                'user_id'                => $userId,
                'session_guest_id'       => $guestId,
                'score_r'                => (int) round($scores->scoreR()),
                'score_i'                => (int) round($scores->scoreI()),
                'score_a'                => (int) round($scores->scoreA()),
                'score_s'                => (int) round($scores->scoreS()),
                'score_e'                => (int) round($scores->scoreE()),
                'score_c'                => (int) round($scores->scoreC()),
                'code_holland'           => $scores->trigram,
                'statut'                 => $progress->isCompleted
                                            ? ProfileRiasec::STATUT_COMPLET
                                            : ProfileRiasec::STATUT_EN_COURS,
                'nb_questions_repondues' => $progress->answered,
                'nb_questions_total'     => $progress->total,
                'score_coherence'        => $scores->coherenceScore,
                'interpretation'         => $interp,
                'complete_at'            => $progress->isCompleted ? now() : null,
            ]
        );
    }

    // ══════════════════════════════════════════════════════════════════════
    // 8. INTERPRÉTATION TEXTUELLE (sans IA)
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Génère une interprétation textuelle structurée du profil RIASEC.
     *
     * Logique :
     *  - Décrit les 3 dimensions dominantes (trigramme)
     *  - Propose des métiers et filières compatibles
     *  - Adapte le ton au niveau de confiance (score de cohérence)
     *  - Identifie les forces principales
     *
     * @param  RiasecScoreDTO $scores
     * @return array          Tableau structuré pour stockage dans ProfileRiasec::interpretation
     */
    public function generateInterpretation(RiasecScoreDTO $scores): array
    {
        $trigram  = $scores->trigram;
        $dominant = $scores->dominantDimension();
        $top3     = str_split($trigram);

        // Profils des 3 dimensions dominantes
        $profiles = array_map(
            fn ($dim) => self::DIMENSION_PROFILES[$dim] ?? [],
            $top3
        );

        // Filières fusionnées (intersection des suggestions)
        $allFilieres = collect($profiles)
            ->flatMap(fn ($p) => $p['filieres'] ?? [])
            ->unique()
            ->values()
            ->all();

        // Métiers fusionnés
        $allMetiers = collect($profiles)
            ->flatMap(fn ($p) => $p['metiers'] ?? [])
            ->unique()
            ->values()
            ->all();

        // Forces fusionnées
        $allForces = collect($profiles)
            ->flatMap(fn ($p) => $p['forces'] ?? [])
            ->unique()
            ->values()
            ->all();

        // Nuance selon la cohérence
        $fiabiliteNote = match (true) {
            $scores->coherenceScore >= 80 => 'Ce profil présente un niveau de cohérence excellent. Les résultats sont très fiables.',
            $scores->coherenceScore >= 60 => 'Ce profil est globalement cohérent. Les résultats sont fiables.',
            $scores->coherenceScore >= 40 => 'Ce profil montre une cohérence modérée. Certaines réponses semblent contradictoires.',
            default                       => 'La cohérence de ce profil est faible. Il est conseillé de repasser le test avec plus d\'attention.',
        };

        return [
            'trigram'          => $trigram,
            'dominant'         => $dominant,
            'profil_label'     => self::DIMENSION_PROFILES[$dominant]['label'] ?? '',
            'profil_emoji'     => self::DIMENSION_PROFILES[$dominant]['emoji'] ?? '',
            'description'      => $this->buildProfileDescription($top3, $scores),
            'forces'           => array_slice($allForces, 0, 6),
            'metiers_suggeres' => array_slice($allMetiers, 0, 8),
            'filieres_suggerees' => array_slice($allFilieres, 0, 8),
            'scores_detail'    => $scores->toArray(),
            'fiabilite'        => $fiabiliteNote,
            'coherence_score'  => $scores->coherenceScore,
            'generated_at'     => now()->toIso8601String(),
        ];
    }

    // ══════════════════════════════════════════════════════════════════════
    // MÉTHODES PRIVÉES — COHÉRENCE & UTILITAIRES
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Calcule le score de cohérence interne (0-100).
     *
     * Principe : compare les écarts intra-dimension entre questions similaires.
     * Un fort écart entre deux questions de même dimension → incohérence.
     *
     * Algorithme simplifié :
     *  - Pour chaque dimension, calcule la variance des réponses
     *  - Une variance élevée signifie des réponses contradictoires
     *  - Score cohérence = 100 - (variance_moy_normalisée × 100)
     */
    private function calculateCoherenceScore(Collection $answers, array $countByDim): int
    {
        if ($answers->isEmpty()) {
            return 0;
        }

        $varianceByDim = [];

        foreach (array_keys(QuestionRiasec::DIMENSIONS) as $dim) {
            $dimAnswers = $answers->filter(
                fn ($a) => $a->question?->dimension === $dim
            )->pluck('valeur');

            if ($dimAnswers->count() < 2) {
                continue;
            }

            $mean     = $dimAnswers->avg();
            $variance = $dimAnswers->map(fn ($v) => pow($v - $mean, 2))->avg();
            $varianceByDim[$dim] = $variance;
        }

        if (empty($varianceByDim)) {
            return 100;
        }

        // Variance max théorique sur échelle 1-5 ≈ 4.0
        $maxVariance  = 4.0;
        $avgVariance  = array_sum($varianceByDim) / count($varianceByDim);
        $coherence    = max(0, 100 - (int) round(($avgVariance / $maxVariance) * 100));

        return min(100, $coherence);
    }

    /**
     * Indique si une question doit être inversée (question de contrôle de cohérence).
     */
    private function isInvertedQuestion(QuestionRiasec $question): bool
    {
        // Vérifie si la source contient un marqueur d'inversion
        // Convention : ajouter "[INV]" dans le champ source de la question inversée
        if ($question->source && str_contains($question->source, '[INV]')) {
            return true;
        }

        return false;
    }

    /**
     * Inverse un score Likert (6 - valeur) ou boolean (1 - valeur).
     */
    private function invertScore(int $valeur, string $typeReponse): int
    {
        return match ($typeReponse) {
            'likert'  => (self::LIKERT_MIN + self::LIKERT_MAX) - $valeur, // 6 - valeur
            'boolean' => 1 - $valeur,
            default   => $valeur,
        };
    }

    /**
     * Retourne les IDs de questions déjà répondues dans une session.
     *
     * @return array<int>
     */
    private function getAnsweredQuestionIds(?int $userId, string $sessionId): array
    {
        return AnswerRiasec::session($sessionId)
            ->pluck('question_id')
            ->all();
    }

    /**
     * Construit le texte de description du profil combiné (jusqu'à 3 dimensions).
     */
    private function buildProfileDescription(array $top3, RiasecScoreDTO $scores): string
    {
        $primary   = self::DIMENSION_PROFILES[$top3[0]] ?? null;
        $secondary = self::DIMENSION_PROFILES[$top3[1]] ?? null;

        if (! $primary) {
            return 'Profil RIASEC en cours d\'analyse.';
        }

        $desc = $primary['description'];

        if ($secondary) {
            $label2 = strtolower($secondary['label']);
            $score2 = round($scores->normalizedScores[$top3[1]] ?? 0);

            if ($score2 >= 60) {
                $desc .= " Tu as également un fort penchant {$label2}, ce qui enrichit ton profil avec des compétences complémentaires.";
            } elseif ($score2 >= 40) {
                $desc .= " Une tendance {$label2} secondaire vient nuancer ton profil.";
            }
        }

        return $desc;
    }

    // ══════════════════════════════════════════════════════════════════════
    // UTILITAIRES PUBLICS
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Génère un nouvel UUID v4 de session de test.
     * À appeler une seule fois au démarrage du test.
     */
    public function generateSessionId(): string
    {
        return (string) Str::uuid();
    }

    /**
     * Invalide tous les caches liés à une session (après réponse, fin de test).
     */
    public function invalidateSessionCache(string $sessionId): void
    {
        Cache::forget("riasec.scores.{$sessionId}");
    }

    /**
     * Retourne les libellés de profil pour une dimension donnée.
     *
     * @return array{label:string, emoji:string, description:string, forces:array, metiers:array, filieres:array}|null
     */
    public function getDimensionProfile(string $dimension): ?array
    {
        return self::DIMENSION_PROFILES[strtoupper($dimension)] ?? null;
    }
}
