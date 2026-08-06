<?php

namespace App\DataTransferObjects;

final class KnowledgeAnswer
{
    /**
     * @param array<int, array{
     *     id:int,
     *     title:string,
     *     score:float
     * }> $sources
     */
    public function __construct(
        public readonly string $answer,

        public readonly array $sources = [],

        public readonly ?float $confidence = null,
    ) {}

    public function toArray(): array
    {
        return [

            'answer' => $this->answer,

            'sources' => $this->sources,

            'confidence' => $this->confidence,

        ];
    }
}