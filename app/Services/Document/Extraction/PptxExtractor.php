<?php

namespace App\Services\Document\Extraction;

use App\Contracts\Document\FileExtractorInterface;
use App\Exceptions\Document\ExtractionFailedException;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Shape\RichText;
use Throwable;

final class PptxExtractor implements FileExtractorInterface
{
    public function supports(string $mimeType, string $extension): bool
    {
        return $extension === 'pptx'
            || $mimeType === 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
    }

    public function extract(string $absolutePath, string $originalFilename): ExtractedDocument
    {
        $start = microtime(true);

        try {

            $presentation = IOFactory::load($absolutePath);

        } catch (Throwable $e) {

            throw new ExtractionFailedException(
                'Gagal membaca file PPTX. File mungkin rusak.',
                previous: $e
            );

        }

        $blocks = [];

        $slideIndex = 0;

        foreach ($presentation->getAllSlides() as $slide) {

            $slideIndex++;

            $texts = [];

            foreach ($slide->getShapeCollection() as $shape) {

                if (! $shape instanceof RichText) {
                    continue;
                }

                foreach ($shape->getParagraphs() as $paragraph) {

                    $line = '';

                    foreach ($paragraph->getRichTextElements() as $element) {

                        if (method_exists($element, 'getText')) {
                            $line .= $element->getText();
                        }

                    }

                    if (trim($line) !== '') {
                        $texts[] = trim($line);
                    }

                }

            }

            if (empty($texts)) {
                continue;
            }

            $title = array_shift($texts);

            $content = implode("\n", $texts);

            $blocks[] = "[Slide {$slideIndex}]\nTitle: {$title}\n\nContent:\n{$content}";

        }

        $text = trim(implode("\n\n", $blocks));

        if ($text === '') {

            throw new ExtractionFailedException(
                'Tidak ada teks yang dapat diekstrak dari slide manapun.'
            );

        }

        return new ExtractedDocument(
            text: $text,
            extractionMethod: 'pptx',
            confidence: null,
            metadata: [
                'mime_type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'original_filename' => $originalFilename,
                'slide_count' => $slideIndex,
                'processing_time' => round(microtime(true) - $start, 2),
            ],
        );
    }
}