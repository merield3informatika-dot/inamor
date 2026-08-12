<?php

namespace App\Services\Document\Extraction;

use App\Contracts\Document\FileExtractorInterface;
use App\Exceptions\Document\UnsupportedDocumentException;

final class DocumentExtractor
{
    /**
     * @param FileExtractorInterface[] $extractors
     */
    public function __construct(
        private readonly array $extractors,
    ) {
    }

    public function extract(
        string $absolutePath,
        string $mimeType,
        string $originalFilename,
    ): ExtractedDocument {

        $extension = strtolower(
            pathinfo($originalFilename, PATHINFO_EXTENSION)
        );

        foreach ($this->extractors as $extractor) {

            if ($extractor->supports($mimeType, $extension)) {
                return $extractor->extract($absolutePath, $originalFilename);
            }

        }

        throw new UnsupportedDocumentException(
            "Tipe file tidak didukung (mime: {$mimeType}, extension: {$extension})."
        );
    }
}