<?php

namespace App\Services\Document\Extraction;

use App\Contracts\Document\FileExtractorInterface;
use App\Exceptions\Document\ExtractionFailedException;

final class OcrExtractor implements FileExtractorInterface
{
    private const SUPPORTED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    private const SUPPORTED_MIMES = ['image/jpeg', 'image/png', 'image/webp'];

    public function __construct(
        private readonly OcrEngine $ocrEngine,
    ) {
    }

    public function supports(string $mimeType, string $extension): bool
    {
        return in_array($extension, self::SUPPORTED_EXTENSIONS, true)
            || in_array($mimeType, self::SUPPORTED_MIMES, true);
    }

    public function extract(string $absolutePath, string $originalFilename): ExtractedDocument
    {
        $start = microtime(true);

        $result = $this->ocrEngine->recognize($absolutePath);

        $text = trim($result->text);

        if ($text === '') {

            throw new ExtractionFailedException(
                'OCR tidak menemukan teks yang dapat dibaca pada gambar ini.'
            );

        }

        return new ExtractedDocument(
            text: $text,
            extractionMethod: 'ocr',
            confidence: $result->confidence,
            metadata: [
                'mime_type' => mime_content_type($absolutePath) ?: null,
                'original_filename' => $originalFilename,
                'ocr_used' => true,
                'ocr_language' => config('document.ocr.language', 'ind'),
                'processing_time' => round(microtime(true) - $start, 2),
            ],
        );
    }
}