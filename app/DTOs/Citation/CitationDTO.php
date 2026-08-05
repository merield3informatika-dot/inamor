<?php

namespace App\DTOs\Citation;

/**
 * Immutable value object representing a single citation, regardless
 * of the underlying source type (document, manual knowledge, calendar
 * event, announcement, etc).
 *
 * This is the atomic unit that flows through:
 *   raw source -> CitationBuilder -> CitationDTO -> CitationCollection
 *                                                  -> CitationFormatter
 *                                                  -> Blade components
 */
final class CitationDTO
{
    public function __construct(
        public readonly string $id,
        public readonly CitationSourceType $sourceType,
        public readonly int $sourceId,
        public readonly string $title,
        public readonly string $snippet,
        public readonly ?int $pageNumber = null,
        public readonly ?string $url = null,
        public readonly float $relevanceScore = 0.0,
        public readonly ?string $updatedAt = null,
        public readonly array $meta = [],
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            sourceType: CitationSourceType::from($data['source_type']),
            sourceId: (int) $data['source_id'],
            title: $data['title'],
            snippet: $data['snippet'] ?? '',
            pageNumber: $data['page_number'] ?? null,
            url: $data['url'] ?? null,
            relevanceScore: (float) ($data['relevance_score'] ?? 0.0),
            updatedAt: $data['updated_at'] ?? null,
            meta: $data['meta'] ?? [],
        );
    }

    public function withRelevanceScore(float $score): self
    {
        return new self(
            id: $this->id,
            sourceType: $this->sourceType,
            sourceId: $this->sourceId,
            title: $this->title,
            snippet: $this->snippet,
            pageNumber: $this->pageNumber,
            url: $this->url,
            relevanceScore: $score,
            updatedAt: $this->updatedAt,
            meta: $this->meta,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'source_type' => $this->sourceType->value,
            'source_type_label' => $this->sourceType->label(),
            'source_id' => $this->sourceId,
            'title' => $this->title,
            'snippet' => $this->snippet,
            'page_number' => $this->pageNumber,
            'url' => $this->url,
            'relevance_score' => round($this->relevanceScore, 4),
            'updated_at' => $this->updatedAt,
            'meta' => $this->meta,
        ];
    }
}
