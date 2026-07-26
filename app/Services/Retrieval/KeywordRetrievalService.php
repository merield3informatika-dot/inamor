<?php

namespace App\Services\Retrieval;

use App\Contracts\RetrievalServiceInterface;
use App\DataTransferObjects\RetrievedDocument;
use App\Models\Document;
use App\Repositories\DocumentRepository;
use Illuminate\Support\Collection;

final class KeywordRetrievalService implements RetrievalServiceInterface
{
    private readonly int $defaultResultLimit;
    private readonly int $titleWeight;
    private readonly int $headingWeight;
    private readonly int $bodyWeight;
    private readonly int $maxHeadingLength;

    public function __construct(
        private readonly DocumentRepository $documentRepository,
        private readonly QuestionNormalizer $normalizer,
    ) {
        $this->defaultResultLimit = (int) config('knowledge.retrieval.result_limit', 3);
        $this->titleWeight = (int) config('knowledge.retrieval.weights.title', 5);
        $this->headingWeight = (int) config('knowledge.retrieval.weights.heading', 3);
        $this->bodyWeight = (int) config('knowledge.retrieval.weights.body', 1);
        $this->maxHeadingLength = (int) config('knowledge.retrieval.max_heading_length', 80);
    }

    public function search(int $workspaceId, string $question, ?int $limit = null): Collection
    {
        $limit ??= $this->defaultResultLimit;

        $normalized = $this->normalizer->normalize($question);
        $keywords = $this->normalizer->extractKeywords($normalized);

        if ($keywords->isEmpty()) {
            return collect();
        }

        $documents = $this->documentRepository->searchDocuments(
            $workspaceId,
            $keywords->all()
        );

        return $documents
            ->map(fn (Document $document): RetrievedDocument => $this->score($document, $keywords))
            ->filter(fn (RetrievedDocument $document): bool => $document->score > 0)
            ->sortByDesc(fn (RetrievedDocument $document): float => $document->score)
            ->take($limit)
            ->values();
    }

    /**
     * @param Collection<int, string> $keywords
     */
    private function score(Document $document, Collection $keywords): RetrievedDocument
    {
        $title = mb_strtolower($document->title);
        $body = mb_strtolower((string) $document->content?->raw_text);
        $headingLines = $this->extractHeadingLines($body);

        $score = 0;

        foreach ($keywords as $keyword) {
            if (str_contains($title, $keyword)) {
                $score += $this->titleWeight;
            }

            if ($this->matchesAnyHeading($headingLines, $keyword)) {
                $score += $this->headingWeight;
            }

            $score += substr_count($body, $keyword) * $this->bodyWeight;
        }

        return new RetrievedDocument(
            documentId: $document->id,
            title: $document->title,
            content: (string) $document->content?->raw_text,
            score: (float) $score,
        );
    }

    /**
     * @param array<int, string> $headingLines
     */
    private function matchesAnyHeading(array $headingLines, string $keyword): bool
    {
        foreach ($headingLines as $line) {
            if (str_contains($line, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Heuristically treats short, non-sentence-ending lines of extracted
     * PDF text as heading candidates, since raw text extraction does not
     * preserve document structure (bold, font size, etc).
     *
     * @return array<int, string>
     */
    private function extractHeadingLines(string $body): array
    {
        return collect(explode("\n", $body))
            ->map(fn (string $line): string => trim($line))
            ->filter(fn (string $line): bool => $line !== '' && mb_strlen($line) <= $this->maxHeadingLength)
            ->filter(fn (string $line): bool => ! str_ends_with($line, '.'))
            ->values()
            ->all();
    }
}
