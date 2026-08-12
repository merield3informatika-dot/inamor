<?php

namespace App\Services\Document\Extraction;

final class OcrResult
{
    public function __construct(
        public readonly string $text,
        public readonly ?float $confidence,
    ) {
    }
}