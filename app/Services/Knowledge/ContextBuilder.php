<?php

namespace App\Services\Knowledge;

use App\DataTransferObjects\RetrievedDocument;
use Illuminate\Support\Collection;

final class ContextBuilder
{
    private const SEPARATOR_LENGTH = 40;

    private readonly int $maxContextLength;

    public function __construct()
    {
        $this->maxContextLength = (int) config('knowledge.context.max_length', 12000);
    }

    /**
     * @param Collection<int, RetrievedDocument> $documents
     */
    public function build(Collection $documents): string
    {
        $context = '';

        foreach ($documents as $document) {
            $block = $this->formatDocument($document);

            if (mb_strlen($context) + mb_strlen($block) > $this->maxContextLength) {
                $context .= $this->truncateToFit($block, $context);
                break;
            }

            $context .= $block;
        }

        return trim($context);
    }

    private function formatDocument(RetrievedDocument $document): string
    {
        return sprintf(
            "Document: %s\n%s\n%s\n\n",
            $document->title,
            str_repeat('-', self::SEPARATOR_LENGTH),
            $document->content
        );
    }

    private function truncateToFit(string $block, string $existingContext): string
    {
        $remaining = $this->maxContextLength - mb_strlen($existingContext);

        if ($remaining <= 0) {
            return '';
        }

        return mb_substr($block, 0, $remaining);
    }
}
