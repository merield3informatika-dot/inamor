<?php

namespace App\DataTransferObjects;

use App\Models\KnowledgeMemory;

final class KnowledgeSearchResult
{
    /**
     * @param array<int,string> $matchedKeywords
     * @param array<int,string> $matchedAliases
     */
    public function __construct(
        public readonly KnowledgeMemory $memory,
        public readonly int $score,
        public readonly int $confidence,
        public readonly array $matchedKeywords,
        public readonly array $matchedAliases,
        public readonly string $highlight,
        public readonly ?int $pageNumber,
    ) {
    }
}