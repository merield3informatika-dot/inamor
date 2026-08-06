<?php

namespace App\Services\KnowledgeMemory;

final class HighlightBuilder
{
    /**
     * Build a short excerpt around the first matched keyword.
     *
     * @param array<int,string> $keywords
     */
    public function build(
        string $knowledge,
        array $keywords,
        int $window = 180,
    ): string {

        $text = trim(
            preg_replace('/\s+/', ' ', strip_tags($knowledge))
        );

        if ($text === '') {
            return '';
        }

        foreach ($keywords as $keyword) {

            $position = mb_stripos(
                $text,
                $keyword
            );

            if ($position !== false) {

                $start = max(
                    0,
                    $position - (int) ($window / 2)
                );

                $excerpt = mb_substr(
                    $text,
                    $start,
                    $window
                );

                if ($start > 0) {
                    $excerpt = '...' . ltrim($excerpt);
                }

                if (
                    ($start + $window) < mb_strlen($text)
                ) {
                    $excerpt = rtrim($excerpt) . '...';
                }

                return $excerpt;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | No keyword found
        |--------------------------------------------------------------------------
        */

        return mb_strlen($text) > $window
            ? mb_substr($text, 0, $window) . '...'
            : $text;
    }
}