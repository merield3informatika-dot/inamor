<?php

namespace App\ValueObjects\Confidence;

enum ConfidenceLevel: string
{
    case HIGH = 'high';
    case MEDIUM = 'medium';
    case LOW = 'low';

    public static function fromScore(float $score): self
    {
        return match (true) {
            $score >= 75 => self::HIGH,
            $score >= 40 => self::MEDIUM,
            default => self::LOW,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::HIGH => 'High Confidence',
            self::MEDIUM => 'Medium Confidence',
            self::LOW => 'Low Confidence',
        };
    }

    public function colorClass(): string
    {
        return match ($this) {
            self::HIGH => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::MEDIUM => 'bg-amber-50 text-amber-700 border-amber-200',
            self::LOW => 'bg-red-50 text-red-700 border-red-200',
        };
    }

    public function dotClass(): string
    {
        return match ($this) {
            self::HIGH => 'bg-emerald-500',
            self::MEDIUM => 'bg-amber-500',
            self::LOW => 'bg-red-500',
        };
    }
}
