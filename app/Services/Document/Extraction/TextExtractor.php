<?php

namespace App\Services\Document\Extraction;

use App\Contracts\Document\FileExtractorInterface;
use App\Exceptions\Document\ExtractionFailedException;

final class TextExtractor implements FileExtractorInterface
{
    public function supports(string $mimeType, string $extension): bool
    {
        return $extension === 'txt' || $mimeType === 'text/plain';
    }

    public function extract(string $absolutePath, string $originalFilename): ExtractedDocument
    {
        $start = microtime(true);

        $raw = file_get_contents($absolutePath);

        if ($raw === false) {

            throw new ExtractionFailedException('Gagal membaca file TXT.');

        }

        if (! mb_check_encoding($raw, 'UTF-8')) {

            $converted = mb_convert_encoding($raw, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');

            if ($converted !== false) {
                $raw = $converted;
            }

        }

        $raw = str_replace(["\r\n", "\r"], "\n", $raw);

        $text = trim($raw);

        if ($text === '') {

            throw new ExtractionFailedException('File TXT ini kosong.');

        }

        return new ExtractedDocument(
            text: $text,
            extractionMethod: 'txt',
            confidence: null,
            metadata: [
                'mime_type' => 'text/plain',
                'original_filename' => $originalFilename,
                'processing_time' => round(microtime(true) - $start, 2),
            ],
        );
    }
}