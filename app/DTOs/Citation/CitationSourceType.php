<?php

namespace App\DTOs\Citation;

/**
 * Every knowledge source in the system must map to one of these.
 * When PDF/DOCX/XLSX/CSV/OCR/Image/Video ingestion is added later,
 * they all still resolve to SourceType::DOCUMENT - the file type is
 * a Document.mime_type concern, not a citation concern. This is what
 * keeps the citation layer stable as ingestion grows.
 */
enum CitationSourceType: string
{
    case DOCUMENT = 'document';
    case MANUAL_KNOWLEDGE = 'manual_knowledge';
    case CALENDAR_EVENT = 'calendar_event';
    case ANNOUNCEMENT = 'announcement';

    public function label(): string
    {
        return match ($this) {
            self::DOCUMENT => 'Document',
            self::MANUAL_KNOWLEDGE => 'Manual Knowledge',
            self::CALENDAR_EVENT => 'Calendar Event',
            self::ANNOUNCEMENT => 'Announcement',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::DOCUMENT => 'document-text',
            self::MANUAL_KNOWLEDGE => 'light-bulb',
            self::CALENDAR_EVENT => 'calendar',
            self::ANNOUNCEMENT => 'megaphone',
        };
    }

    /**
     * Tailwind color token used consistently by the badge/card components.
     */
    public function colorClass(): string
    {
        return match ($this) {
            self::DOCUMENT => 'bg-blue-50 text-blue-700 border-blue-100',
            self::MANUAL_KNOWLEDGE => 'bg-amber-50 text-amber-700 border-amber-100',
            self::CALENDAR_EVENT => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            self::ANNOUNCEMENT => 'bg-purple-50 text-purple-700 border-purple-100',
        };
    }
}
