<?php

namespace App\Contracts\Document;

use App\Services\Document\Extraction\ExtractedDocument;

interface FileExtractorInterface
{
    /**
     * Whether this extractor can handle the given mime type / extension.
     */
    public function supports(string $mimeType, string $extension): bool;

    /**
     * Extract clean text and metadata from the file at the given path.
     */
    public function extract(string $absolutePath, string $originalFilename): ExtractedDocument;
}