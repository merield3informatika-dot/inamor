<?php

namespace App\DataTransferObjects;

final class RetrievedDocument
{
    public function __construct(
        public readonly int $documentId,
        public readonly string $title,
        public readonly string $content,
        public readonly float $score,
    ) {}
}
