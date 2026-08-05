<?php

namespace App\DTOs\Citation;

use Illuminate\Support\Collection;

/**
 * Typed collection of CitationDTO. Extends Laravel's base Collection so
 * every existing Collection method (map, filter, pluck, ...) still works,
 * while adding domain-specific helpers on top.
 *
 * @extends Collection<int, CitationDTO>
 */
final class CitationCollection extends Collection
{
    public static function fromDTOs(array $citations): self
    {
        return new self($citations);
    }

    public function sortByRelevance(): self
    {
        return $this->sortByDesc(fn (CitationDTO $citation) => $citation->relevanceScore)
            ->values();
    }

    public function groupBySourceType(): Collection
    {
        return $this->groupBy(fn (CitationDTO $citation) => $citation->sourceType->value);
    }

    public function documents(): self
    {
        return $this->filter(
            fn (CitationDTO $citation) => $citation->sourceType === CitationSourceType::DOCUMENT
        )->values();
    }

    public function manualKnowledges(): self
    {
        return $this->filter(
            fn (CitationDTO $citation) => $citation->sourceType === CitationSourceType::MANUAL_KNOWLEDGE
        )->values();
    }

    public function calendarEvents(): self
    {
        return $this->filter(
            fn (CitationDTO $citation) => $citation->sourceType === CitationSourceType::CALENDAR_EVENT
        )->values();
    }

    public function announcements(): self
    {
        return $this->filter(
            fn (CitationDTO $citation) => $citation->sourceType === CitationSourceType::ANNOUNCEMENT
        )->values();
    }

    /**
     * Top N citations by relevance, useful for capping how many sources
     * get shown in the UI or injected into the AI prompt.
     */
    public function top(int $limit = 5): self
    {
        return $this->sortByRelevance()->take($limit)->values();
    }

    public function toArray(): array
    {
        return $this->map(fn (CitationDTO $citation) => $citation->toArray())->all();
    }
}
