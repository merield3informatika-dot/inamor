<?php

namespace App\Services\Document\Extraction;

use App\Contracts\Document\FileExtractorInterface;
use App\Exceptions\Document\ExtractionFailedException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;

final class SpreadsheetExtractor implements FileExtractorInterface
{
    private const SUPPORTED_EXTENSIONS = ['xlsx', 'xls'];

    private const SUPPORTED_MIMES = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel',
    ];

    public function supports(string $mimeType, string $extension): bool
    {
        return in_array($extension, self::SUPPORTED_EXTENSIONS, true)
            || in_array($mimeType, self::SUPPORTED_MIMES, true);
    }

    public function extract(string $absolutePath, string $originalFilename): ExtractedDocument
    {
        $start = microtime(true);

        try {

            $spreadsheet = IOFactory::load($absolutePath);

        } catch (Throwable $e) {

            throw new ExtractionFailedException(
                'Gagal membaca file XLSX. File mungkin rusak.',
                previous: $e
            );

        }

        $blocks = [];

        $sheetMeta = [];

        foreach ($spreadsheet->getAllSheets() as $sheet) {

            $rows = $sheet->toArray(null, true, true, false);

            $rows = array_values(array_filter(
                $rows,
                fn (array $row): bool => ! $this->isEmptyRow($row)
            ));

            if (empty($rows)) {
                continue;
            }

            $lines = ["Sheet: {$sheet->getTitle()}", ''];

            foreach ($rows as $row) {

                $cells = array_map(
                    fn ($cell) => $cell === null ? '' : trim((string) $cell),
                    $row
                );

                $lines[] = implode(' | ', $cells);

            }

            $blocks[] = implode("\n", $lines);

            $sheetMeta[] = [
                'name' => $sheet->getTitle(),
                'rows' => count($rows),
                'columns' => $sheet->getHighestColumn(),
            ];

        }

        $text = trim(implode("\n\n", $blocks));

        if ($text === '') {

            throw new ExtractionFailedException(
                'File XLSX ini tidak memiliki data pada sheet manapun.'
            );

        }

        return new ExtractedDocument(
            text: $text,
            extractionMethod: 'xlsx',
            confidence: null,
            metadata: [
                'mime_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'original_filename' => $originalFilename,
                'sheet_count' => count($sheetMeta),
                'sheets' => $sheetMeta,
                'processing_time' => round(microtime(true) - $start, 2),
            ],
        );
    }

    /**
     * @param array<int,mixed> $row
     */
    private function isEmptyRow(array $row): bool
    {
        foreach ($row as $cell) {

            if ($cell !== null && trim((string) $cell) !== '') {
                return false;
            }

        }

        return true;
    }
}