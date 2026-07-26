<?php

namespace App\Services\Retrieval;

use Illuminate\Support\Collection;

final class QuestionNormalizer
{
    public function normalize(string $question): string
    {
        $question = mb_strtolower($question);
        $question = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $question) ?? '';
        $question = preg_replace('/\s+/', ' ', $question) ?? '';

        return trim($question);
    }

    /**
     * @return Collection<int, string>
     */
    public function extractKeywords(string $normalizedQuestion): Collection
    {
        if ($normalizedQuestion === '') {
            return collect();
        }

        $minLength = (int) config('knowledge.retrieval.min_keyword_length', 2);

        return collect(explode(' ', $normalizedQuestion))
            ->filter(fn (string $word): bool => $word !== '')
            ->reject(fn (string $word): bool => in_array($word, StopWords::LIST, true))
            ->filter(fn (string $word): bool => mb_strlen($word) >= $minLength)
            ->unique()
            ->values();
    }
}
