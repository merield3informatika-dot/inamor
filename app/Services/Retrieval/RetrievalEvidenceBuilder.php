<?php

namespace App\Services\Retrieval;

use Illuminate\Support\Collection;

final class RetrievalEvidenceBuilder
{
    private readonly int $maxHighlights;
    private readonly int $contextBefore;
    private readonly int $contextAfter;

    public function __construct()
    {
        $this->maxHighlights = (int) config(
            'knowledge.retrieval.max_highlights',
            3
        );

        /*
         * Jumlah karakter yang diambil sebelum dan sesudah
         * keyword yang ditemukan.
         */
        $this->contextBefore = (int) config(
            'knowledge.retrieval.highlight_context_before',
            100
        );

        $this->contextAfter = (int) config(
            'knowledge.retrieval.highlight_context_after',
            180
        );
    }

    /**
     * Build concise evidence excerpts around matched keywords.
     *
     * @param Collection<int, string> $keywords
     * @return array<int, array{text: string, page: int|null}>
     */
    public function build(
        string $content,
        Collection $keywords
    ): array {
        if (
            trim($content) === ''
            || $keywords->isEmpty()
        ) {
            return [];
        }

        $lines = preg_split(
            "/\r\n|\r|\n/",
            $content
        ) ?: [];

        $results = [];

        $currentPage = null;

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            /*
             |--------------------------------------------------------------------------
             | Page Detection
             |--------------------------------------------------------------------------
             |
             | Supports:
             |
             | Page 1:
             | Page 2:
             | PAGE 3:
             | Page 4
             |
             */

            if (
                preg_match(
                    '/^Page\s+(\d+)\s*:?\s*$/i',
                    $line,
                    $matches
                )
            ) {
                $currentPage = (int) $matches[1];

                continue;
            }

            /*
             |--------------------------------------------------------------------------
             | Find keyword matches
             |--------------------------------------------------------------------------
             */

            $normalizedLine = mb_strtolower($line);

            foreach ($keywords as $keyword) {
                $keyword = trim(
                    mb_strtolower((string) $keyword)
                );

                if ($keyword === '') {
                    continue;
                }

                $offset = 0;

                while (
                    ($position = mb_stripos(
                        $normalizedLine,
                        $keyword,
                        $offset
                    )) !== false
                ) {
                    /*
                     * Build a short context around the actual match.
                     */
                    $excerpt = $this->buildExcerpt(
                        $line,
                        $position,
                        mb_strlen($keyword)
                    );

                    if ($excerpt === '') {
                        break;
                    }

                    $results[] = [
                        'text' => $excerpt,
                        'page' => $currentPage,
                        '_score' => $this->calculateScore(
                            $line,
                            $keyword
                        ),
                    ];

                    /*
                     * Continue searching after this match.
                     */
                    $offset = $position + max(
                        mb_strlen($keyword),
                        1
                    );
                }
            }
        }

        /*
         |--------------------------------------------------------------------------
         | Sort + Deduplicate
         |--------------------------------------------------------------------------
         */

        return collect($results)
            ->sortByDesc('_score')
            ->unique(
                fn (array $item): string =>
                    ($item['page'] ?? 'null')
                    . '|'
                    . $item['text']
            )
            ->take($this->maxHighlights)
            ->map(
                fn (array $item): array => [
                    'text' => $item['text'],
                    'page' => $item['page'],
                ]
            )
            ->values()
            ->all();
    }

    /**
     * Build a concise excerpt around the matched keyword.
     */
    private function buildExcerpt(
        string $text,
        int $position,
        int $keywordLength
    ): string {
        $textLength = mb_strlen($text);

        if ($textLength === 0) {
            return '';
        }

        /*
         * Start slightly before the keyword.
         */
        $start = max(
            0,
            $position - $this->contextBefore
        );

        /*
         * End slightly after the keyword.
         */
        $end = min(
            $textLength,
            $position
                + $keywordLength
                + $this->contextAfter
        );

        $excerptLength = $end - $start;

        $excerpt = mb_substr(
            $text,
            $start,
            $excerptLength
        );

        /*
         |--------------------------------------------------------------------------
         | Clean Beginning
         |--------------------------------------------------------------------------
         */

        if ($start > 0) {
            /*
             * Don't start halfway through a word.
             */
            $spacePosition = mb_strpos(
                $excerpt,
                ' '
            );

            if (
                $spacePosition !== false
                && $spacePosition < 30
            ) {
                $excerpt = mb_substr(
                    $excerpt,
                    $spacePosition + 1
                );
            }

            $excerpt = '...' . ltrim($excerpt);
        }

        /*
         |--------------------------------------------------------------------------
         | Clean Ending
         |--------------------------------------------------------------------------
         */

        if ($end < $textLength) {
            /*
             * Don't end halfway through a word.
             */
            $lastSpace = mb_strrpos(
                $excerpt,
                ' '
            );

            if (
                $lastSpace !== false
                && $lastSpace > mb_strlen($excerpt) - 40
            ) {
                $excerpt = mb_substr(
                    $excerpt,
                    0,
                    $lastSpace
                );
            }

            $excerpt = rtrim($excerpt) . '...';
        }

        /*
         |--------------------------------------------------------------------------
         | Normalize Whitespace
         |--------------------------------------------------------------------------
         */

        $excerpt = preg_replace(
            '/\s+/u',
            ' ',
            $excerpt
        ) ?? $excerpt;

        return trim($excerpt);
    }

    /**
     * Score an evidence line.
     *
     * More keyword occurrences = stronger evidence.
     */
    private function calculateScore(
        string $line,
        string $keyword
    ): int {
        $normalizedLine = mb_strtolower($line);

        $count = substr_count(
            $normalizedLine,
            $keyword
        );

        /*
         * Give a small bonus when the line is reasonably concise.
         *
         * This prevents giant OCR blocks from always winning
         * simply because they contain the keyword many times.
         */
        $score = $count;

        if (mb_strlen($line) <= 500) {
            $score += 2;
        }

        if (mb_strlen($line) <= 250) {
            $score += 1;
        }

        return $score;
    }
}