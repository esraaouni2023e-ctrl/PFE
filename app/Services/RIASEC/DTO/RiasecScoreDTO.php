<?php

namespace App\Services\RIASEC\DTO;

/**
 * RiasecScoreDTO — Value Object immuable représentant les scores d'un test RIASEC.
 *
 * Centralise les scores bruts, normalisés et le code Holland calculé.
 * Toutes les propriétés sont en lecture seule (readonly PHP 8.1+).
 */
final class RiasecScoreDTO
{
    /** Ordre de priorité Holland en cas d'égalité de score. */
    private const PRIORITY = ['R' => 0, 'I' => 1, 'A' => 2, 'S' => 3, 'E' => 4, 'C' => 5];

    /**
     * @param array<string,int>   $rawScores        Scores bruts par dimension (ex: ['R'=>32,'I'=>40,...])
     * @param array<string,float> $normalizedScores Scores normalisés 0-100 (ex: ['R'=>64.0,'I'=>80.0,...])
     * @param string              $trigram          Code Holland à 3 lettres (ex: "IAS")
     * @param int                 $totalAnswers     Nombre total de réponses comptabilisées
     * @param int                 $coherenceScore   Score de cohérence interne 0-100
     */
    public function __construct(
        public readonly array  $rawScores,
        public readonly array  $normalizedScores,
        public readonly string $trigram,
        public readonly int    $totalAnswers,
        public readonly int    $coherenceScore,
    ) {}

    // ── Accesseurs de commodité ────────────────────────────────────────────

    public function scoreR(): float { return $this->normalizedScores['R'] ?? 0.0; }
    public function scoreI(): float { return $this->normalizedScores['I'] ?? 0.0; }
    public function scoreA(): float { return $this->normalizedScores['A'] ?? 0.0; }
    public function scoreS(): float { return $this->normalizedScores['S'] ?? 0.0; }
    public function scoreE(): float { return $this->normalizedScores['E'] ?? 0.0; }
    public function scoreC(): float { return $this->normalizedScores['C'] ?? 0.0; }

    /** Retourne la dimension dominante (première lettre du trigram). */
    public function dominantDimension(): string
    {
        return $this->trigram[0] ?? 'R';
    }

    /** Scores normalisés triés par valeur décroissante. */
    public function sortedScores(): array
    {
        $scores = $this->normalizedScores;
        uasort($scores, fn ($a, $b) => $b <=> $a);
        return $scores;
    }

    /** Sérialise le DTO en tableau pour stockage JSON (ProfileRiasec::interpretation). */
    public function toArray(): array
    {
        return [
            'raw'        => $this->rawScores,
            'normalized' => array_map(fn ($s) => round($s, 2), $this->normalizedScores),
            'trigram'    => $this->trigram,
            'total'      => $this->totalAnswers,
            'coherence'  => $this->coherenceScore,
        ];
    }
}
