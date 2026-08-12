<?php

namespace App\Services\Document\Extraction;

use App\Contracts\Document\FileExtractorInterface;
use App\Exceptions\Document\ExtractionFailedException;
use PhpOffice\PhpWord\Element\ListItem;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\IOFactory;
use Throwable;

final class DocxExtractor implements FileExtractorInterface
{
    public function supports(string $mimeType, string $extension): bool
    {
        return $extension === 'docx'
            || $mimeType === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    }

    public function extract(string $absolutePath, string $originalFilename): ExtractedDocument
    {
        $start = microtime(true);

        try {

            $phpWord = IOFactory::load($absolutePath);

        } catch (Throwable $e) {

            throw new ExtractionFailedException(
                'Gagal membaca file DOCX. File mungkin rusak atau bukan format DOCX yang valid.',
                previous: $e
            );

        }

        $lines = [];

        foreach ($phpWord->getSections() as $section) {

            foreach ($section->getElements() as $element) {

                $rendered = $this->renderElement($element);

                if ($rendered !== null && trim($rendered) !== '') {
                    $lines[] = $rendered;
                }

            }

        }

        $text = trim(implode("\n", $lines));

        if ($text === '') {

            throw new ExtractionFailedException(
                'Tidak ada teks yang dapat diekstrak dari file DOCX ini.'
            );

        }

        return new ExtractedDocument(
            text: $text,
            extractionMethod: 'docx',
            confidence: null,
            metadata: [
                'mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'original_filename' => $originalFilename,
                'processing_time' => round(microtime(true) - $start, 2),
            ],
        );
    }

    private function renderElement(object $element): ?string
    {
        if ($element instanceof Title) {

            $prefix = str_repeat('#', min(6, $element->getDepth() + 1));

            return $prefix . ' ' . $this->extractTitleText($element);

        }

        if ($element instanceof Text) {

            return $element->getText();

        }

        if ($element instanceof TextRun) {

            $buffer = [];

            foreach ($element->getElements() as $child) {

                if ($child instanceof Text) {
                    $buffer[] = $child->getText();
                }

            }

            return implode('', $buffer);

        }

        if ($element instanceof ListItem) {

            return '- ' . $this->extractTitleText($element);

        }

        if ($element instanceof Table) {

            $rows = [];

            foreach ($element->getRows() as $row) {

                $cells = [];

                foreach ($row->getCells() as $cell) {

                    $cellText = [];

                    foreach ($cell->getElements() as $cellElement) {

                        $rendered = $this->renderElement($cellElement);

                        if ($rendered !== null) {
                            $cellText[] = $rendered;
                        }

                    }

                    $cells[] = implode(' ', $cellText);

                }

                $rows[] = implode(' | ', $cells);

            }

            return implode("\n", $rows);

        }

        return null;
    }

    private function extractTitleText(object $element): string
    {
        $textObject = method_exists($element, 'getText')
            ? $element->getText()
            : (method_exists($element, 'getTextObject') ? $element->getTextObject() : '');

        if (is_string($textObject)) {
            return $textObject;
        }

        if (is_object($textObject) && method_exists($textObject, 'getElements')) {

            $buffer = [];

            foreach ($textObject->getElements() as $child) {

                if (method_exists($child, 'getText')) {
                    $buffer[] = $child->getText();
                }

            }

            return implode('', $buffer);

        }

        return '';
    }
}