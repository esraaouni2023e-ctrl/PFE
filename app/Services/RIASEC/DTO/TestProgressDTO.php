<?php

namespace App\Services\RIASEC\DTO;

/**
 * TestProgressDTO — Value Object représentant la progression d'un test en cours.
 *
 * Permet au contrôleur et à la vue de connaître l'avancement précis
 * sans coupler la logique de calcul à la présentation.
 */
final class TestProgressDTO
{
    public function __construct(
        public readonly int    $answered,
        public readonly int    $total,
        public readonly float  $percentage,
        public readonly bool   $isCompleted,
        public readonly array  $answeredByDimension,   // ['R'=>3,'I'=>2,...]
        public readonly array  $remainingByDimension,  // ['R'=>5,'I'=>6,...]
        public readonly ?int   $lastQuestionId,
        public readonly string $sessionId,
    ) {}

    /** Retourne le nombre de questions restantes globalement. */
    public function remaining(): int
    {
        return $this->total - $this->answered;
    }

    /** Estimation du temps restant en minutes (base : 20s / question). */
    public function estimatedMinutesLeft(): int
    {
        return (int) ceil(($this->remaining() * 20) / 60);
    }

    public function toArray(): array
    {
        return [
            'answered'              => $this->answered,
            'total'                 => $this->total,
            'percentage'            => round($this->percentage, 1),
            'is_completed'          => $this->isCompleted,
            'answered_by_dimension' => $this->answeredByDimension,
            'remaining_by_dimension'=> $this->remainingByDimension,
            'last_question_id'      => $this->lastQuestionId,
            'session_id'            => $this->sessionId,
            'estimated_minutes_left'=> $this->estimatedMinutesLeft(),
        ];
    }
}
