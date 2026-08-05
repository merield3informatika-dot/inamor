<?php

namespace App\DTOs\Citation;

/**
 * Structured "preview" of a source, derived from a CitationDTO plus
 * the raw source text. This is what the Source Preview panel/modal
 * in the UI renders - separate from CitationDTO because a preview
 * carries heavier, on-demand data (full highlighted paragraph) that
 * we don't want loaded for every citation in a chat answer, only when
 * the user opens the preview.
 */
final class SourcePreviewDTO
{
    public function __construct(
        public readonly string $citationId,
        public readonly string $title,
        public readonly CitationSourceType $sourceType,
        public readonly string $highlightedParagraph,
        public readonly string $snippet,
        public readonly ?int $pageNumber,
        public readonly float $confidenceScore,
    ) {
    }

    public function toArray(): array
    {
        return [
            'citation_id' => $this->citationId,
            'title' => $this->title,
            'source_type' => $this->sourceType->value,
            'highlighted_paragraph' => $this->highlightedParagraph,
            'snippet' => $this->snippet,
            'page_number' => $this->pageNumber,
            'confidence_score' => round($this->confidenceScore, 1),
        ];
    }
}
