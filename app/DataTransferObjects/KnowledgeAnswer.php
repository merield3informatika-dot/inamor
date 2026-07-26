<?php

namespace App\DataTransferObjects;

final class KnowledgeAnswer
{
    /**
     * @param array<int, string> $sources
     */
    public function __construct(
        public readonly string $answer,
        public readonly array $sources,
    ) {}

    /**
     * @return array{answer: string, sources: array<int, string>}
     */
    public function toArray(): array
    {
        return [
            'answer'  => $this->answer,
            'sources' => $this->sources,
        ];
    }
}
