<?php

namespace App\Services\Document\Extraction;

use App\Exceptions\Document\ExtractionFailedException;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Throwable;

final class OcrEngine
{
    public function recognize(string $imagePath, ?string $language = null): OcrResult
    {
        if (! (bool) config('document.ocr.enabled', false)) {

            throw new ExtractionFailedException(
                'OCR tidak diaktifkan. Set DOCUMENT_OCR_ENABLED=true pada .env.'
            );

        }

        $binary = (string) config('document.ocr.binary_path', '');

        if ($binary === '' || ! is_file($binary)) {

            throw new ExtractionFailedException(
                'Binary Tesseract OCR tidak ditemukan. Periksa DOCUMENT_OCR_BINARY_PATH pada .env.'
            );

        }

        $lang = $language ?? (string) config('document.ocr.language', 'ind');

        $process = new Process([
            $binary,
            $imagePath,
            'stdout',
            '-l', $lang,
            '--psm', (string) config('document.ocr.psm', 3),
            'tsv',
        ]);

        $process->setTimeout((int) config('document.ocr.timeout', 120));

        try {

            $process->run();

        } catch (Throwable $e) {

            throw new ExtractionFailedException(
                'Proses OCR gagal dijalankan.',
                previous: $e
            );

        }

        if (! $process->isSuccessful()) {

            throw new ExtractionFailedException(
                'OCR gagal memproses file: ' . $this->safeErrorMessage($process->getErrorOutput())
            );

        }

        return $this->parseTsv($process->getOutput());
    }

    private function parseTsv(string $tsv): OcrResult
    {
        $lines = explode("\n", trim($tsv));

        array_shift($lines); // header row

        $words = [];

        $confidences = [];

        foreach ($lines as $line) {

            if (trim($line) === '') {
                continue;
            }

            $columns = explode("\t", $line);

            if (count($columns) < 12) {
                continue;
            }

            $confidence = (float) $columns[10];

            $text = trim($columns[11]);

            if ($text === '') {
                continue;
            }

            $words[] = $text;

            if ($confidence >= 0) {
                $confidences[] = $confidence;
            }

        }

        $text = implode(' ', $words);

        $confidence = $confidences !== []
            ? round(array_sum($confidences) / count($confidences), 2)
            : null;

        return new OcrResult(text: $text, confidence: $confidence);
    }

    private function safeErrorMessage(string $raw): string
    {
        return Str::limit(trim($raw), 200);
    }
}