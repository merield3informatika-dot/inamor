<?php

namespace App\DataTransferObjects;

final class KnowledgeAnswer
{
    /**
     * @param array<int, string> $sources
     */
    public function __construct(
        public readonly string $answer,

        public readonly array $sources = [],

        public readonly ?float $confidence = null,
    ) {}

    /**
     * @return array{
     *     answer:string,
     *     sources:array<int,string>,
     *     confidence:float|null
     * }
     */
    public function toArray(): array
    {
        return [

            'answer' => $this->answer,

            'sources' => $this->sources,

            'confidence' => $this->confidence,

        ];
    }
}