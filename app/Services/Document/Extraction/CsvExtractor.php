<?php

namespace App\Services\Document\Extraction;

use App\Contracts\Document\FileExtractorInterface;
use App\Exceptions\Document\ExtractionFailedException;

final class CsvExtractor implements FileExtractorInterface
{
    public function supports(string $mimeType, string $extension): bool
    {
        return $extension === 'csv' || $mimeType === 'text/csv';
    }

    public function extract(string $absolutePath, string $originalFilename): ExtractedDocument
    {
        $start = microtime(true);

        $raw = file_get_contents($absolutePath);

        if ($raw === false) {

            throw new ExtractionFailedException('Gagal membaca file CSV.');

        }

        $raw = $this->stripBom($raw);

        $delimiter = $this->detectDelimiter($raw);

        $lines = preg_split('/\r\n|\r|\n/', trim($raw)) ?: [];

        $rows = [];

        foreach ($lines as $line) {

            if (trim($line) === '') {
                continue;
            }

            $rows[] = str_getcsv($line, $delimiter);

        }

        if (empty($rows)) {

            throw new ExtractionFailedException('File CSV kosong atau tidak dapat dibaca.');

        }

        $textLines = array_map(
            fn (array $row): string => implode(
                ' | ',
                array_map(fn ($cell) => trim((string) $cell), $row)
            ),
            $rows
        );

        return new ExtractedDocument(
            text: implode("\n", $textLines),
            extractionMethod: 'csv',
            confidence: null,
            metadata: [
                'mime_type' => 'text/csv',
                'original_filename' => $originalFilename,
                'delimiter' => $delimiter,
                'row_count' => count($rows),
                'processing_time' => round(microtime(true) - $start, 2),
            ],
        );
    }

    private function stripBom(string $text): string
    {
        return str_starts_with($text, "\xEF\xBB\xBF") ? substr($text, 3) : $text;
    }

    private function detectDelimiter(string $sample): string
    {
        $firstLine = strtok($sample, "\n") ?: '';

        $candidates = [',', ';', "\t", '|'];

        $best = ',';

        $bestCount = 0;

        foreach ($candidates as $delimiter) {

            $count = substr_count($firstLine, $delimiter);

            if ($count > $bestCount) {
                $bestCount = $count;
                $best = $delimiter;
            }

        }

        return $best;
    }
}