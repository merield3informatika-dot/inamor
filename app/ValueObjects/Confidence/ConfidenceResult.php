<?php

namespace App\ValueObjects\Confidence;

/**
 * Immutable result of a confidence calculation. Score is 0-100.
 * Bands: >=75 high, 40-74 medium, <40 low. Kept as a single source
 * of truth so the badge component, API responses, and analytics
 * aggregation always agree on what "high/medium/low" means.
 */
final class ConfidenceResult
{
    public function __construct(
        public readonly float $score,
        public readonly ConfidenceLevel $level,
        public readonly array $factors = [],
    ) {
    }

    public static function fromScore(float $score, array $factors = []): self
    {
        $score = max(0.0, min(100.0, $score));

        return new self($score, ConfidenceLevel::fromScore($score), $factors);
    }

    public function toArray(): array
    {
        return [
            'score' => round($this->score, 1),
            'level' => $this->level->value,
            'label' => $this->level->label(),
            'color_class' => $this->level->colorClass(),
            'factors' => $this->factors,
        ];
    }
}
