<?php

namespace App\Services\Document\Extraction;

use App\Contracts\Document\FileExtractorInterface;
use App\Exceptions\Document\ExtractionFailedException;
use Smalot\PdfParser\Parser;
use Throwable;

final class PdfExtractor implements FileExtractorInterface
{
    public function __construct(
        private readonly Parser $parser,
        private readonly PdfPageRasterizer $rasterizer,
        private readonly OcrEngine $ocrEngine,
    ) {
    }

    public function supports(string $mimeType, string $extension): bool
    {
        return $extension === 'pdf' || $mimeType === 'application/pdf';
    }

    public function extract(string $absolutePath, string $originalFilename): ExtractedDocument
    {
        $start = microtime(true);

        try {

            $pdf = $this->parser->parseFile($absolutePath);

        } catch (Throwable $e) {

            throw new ExtractionFailedException(
                'Gagal membaca file PDF. File mungkin rusak atau terenkripsi.',
                previous: $e
            );

        }

        $pages = $pdf->getPages();

        $pageCount = count($pages);

        $rawText = trim($pdf->getText());

        $minTextLength = (int) config('document.extraction.pdf.min_text_length', 50);

        $cleanedLength = mb_strlen(preg_replace('/\s+/', '', $rawText) ?? '');

        if ($cleanedLength >= $minTextLength) {

            return new ExtractedDocument(
                text: $this->cleanText($rawText),
                extractionMethod: 'pdf',
                confidence: null,
                metadata: [
                    'mime_type' => 'application/pdf',
                    'original_filename' => $originalFilename,
                    'page_count' => $pageCount,
                    'ocr_used' => false,
                    'processing_time' => round(microtime(true) - $start, 2),
                ],
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Insufficient text detected — treat as scanned PDF, fall back to OCR
        |--------------------------------------------------------------------------
        */

        if (! (bool) config('document.ocr.enabled', false)) {

            throw new ExtractionFailedException(
                'PDF terdeteksi sebagai hasil scan (teks minim), namun OCR tidak diaktifkan pada sistem ini.'
            );

        }

        $pageTexts = [];

        $confidences = [];

        for ($i = 0; $i < $pageCount; $i++) {

            $imagePath = $this->rasterizer->rasterizePage($absolutePath, $i);

            try {

                $result = $this->ocrEngine->recognize($imagePath);

                $pageTexts[] = 'Page ' . ($i + 1) . ":\n" . trim($result->text);

                if ($result->confidence !== null) {
                    $confidences[] = $result->confidence;
                }

            } finally {

                @unlink($imagePath);

            }

        }

        $combinedText = implode("\n\n", $pageTexts);

        return new ExtractedDocument(
            text: $this->cleanText($combinedText),
            extractionMethod: 'ocr',
            confidence: $confidences !== []
                ? round(array_sum($confidences) / count($confidences), 2)
                : null,
            metadata: [
                'mime_type' => 'application/pdf',
                'original_filename' => $originalFilename,
                'page_count' => $pageCount,
                'pages_processed' => count($pageTexts),
                'ocr_used' => true,
                'ocr_language' => config('document.ocr.language', 'ind'),
                'processing_time' => round(microtime(true) - $start, 2),
            ],
        );
    }

    private function cleanText(string $text): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        $text = preg_replace('/\n{3,}/', "\n\n", $text) ?? $text;

        $text = preg_replace('/[ \t]{2,}/', ' ', $text) ?? $text;

        return trim($text);
    }
}