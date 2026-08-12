<?php

namespace App\Services\Document\Extraction;

final class ExtractedDocument
{
    /**
     * @param array<string,mixed> $metadata
     */
    public function __construct(
        public readonly string $text,
        public readonly string $extractionMethod,
        public readonly ?float $confidence,
        public readonly array $metadata = [],
    ) {
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'extraction_method' => $this->extractionMethod,
            'confidence' => $this->confidence,
            'metadata' => $this->metadata,
        ];
    }
}