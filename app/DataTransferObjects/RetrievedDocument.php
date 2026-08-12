<?php

namespace App\DataTransferObjects;

final class RetrievedDocument
{
    /**
     * @param array<int, array{
     *     text: string,
     *     page: int|null
     * }> $highlights
     */
    public function __construct(
        public readonly int $documentId,
        public readonly string $title,
        public readonly string $content,
        public readonly float $score,
        public readonly array $highlights = [],
    ) {
    }
}