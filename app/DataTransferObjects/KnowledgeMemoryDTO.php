<?php

namespace App\DataTransferObjects;

final class KnowledgeMemoryDTO
{
    /**
     * @param array<int, string> $aliases
     */
    public function __construct(
        public readonly string $title,
        public readonly string $knowledge,
        public readonly ?int $pageNumber = null,
        public readonly ?float $confidence = null,
        public readonly array $aliases = [],
    ) {
    }

    public function toArray(): array
    {
        return [

            'title' => $this->title,

            'knowledge' => $this->knowledge,

            'page_number' => $this->pageNumber,

            'confidence' => $this->confidence,

            'aliases' => $this->aliases,

        ];
    }
}