<?php

namespace App\Services\Retrieval;

use App\Contracts\RetrievalServiceInterface;
use App\DataTransferObjects\RetrievedDocument;
use App\Models\Document;
use App\Models\ManualKnowledge;
use App\Repositories\DocumentRepository;
use App\Repositories\ManualKnowledgeRepository;
use Illuminate\Support\Collection;

final class KeywordRetrievalService implements RetrievalServiceInterface
{
    private readonly int $defaultResultLimit;
    private readonly int $minimumScore;
    private readonly int $titleWeight;
    private readonly int $headingWeight;
    private readonly int $bodyWeight;
    private readonly int $maxHeadingLength;

    public function __construct(
        private readonly DocumentRepository $documentRepository,
        private readonly ManualKnowledgeRepository $manualKnowledgeRepository,
        private readonly QuestionNormalizer $normalizer,
        private readonly RetrievalEvidenceBuilder $evidenceBuilder,
    ) {
        $this->defaultResultLimit = (int) config(
            'knowledge.retrieval.result_limit',
            3
        );

        $this->minimumScore = (int) config(
            'knowledge.retrieval.minimum_score',
            4
        );

        $this->titleWeight = (int) config(
            'knowledge.retrieval.weights.title',
            5
        );

        $this->headingWeight = (int) config(
            'knowledge.retrieval.weights.heading',
            3
        );

        $this->bodyWeight = (int) config(
            'knowledge.retrieval.weights.body',
            1
        );

        $this->maxHeadingLength = (int) config(
            'knowledge.retrieval.max_heading_length',
            80
        );
    }

    /**
     * Find the most relevant documents and their evidence.
     *
     * @return Collection<int, RetrievedDocument>
     */
    public function search(
        int $workspaceId,
        string $question,
        ?int $limit = null
    ): Collection {
        $limit ??= $this->defaultResultLimit;

        $normalized = $this->normalizer->normalize($question);

        $keywords = $this->normalizer->extractKeywords(
            $normalized
        );

        if ($keywords->isEmpty()) {
            return collect();
        }

        /*
         * ---------------------------------------------------------
         * Document retrieval
         * ---------------------------------------------------------
         */

        $documents = $this->documentRepository->searchDocuments(
            $workspaceId,
            $keywords->all()
        );

        /*
         * ---------------------------------------------------------
         * Manual knowledge retrieval
         * ---------------------------------------------------------
         */

        $manualKnowledges = $this->manualKnowledgeRepository->searchKnowledge(
            $workspaceId,
            $keywords->all()
        );

        /*
         * ---------------------------------------------------------
         * Score documents
         * ---------------------------------------------------------
         */

        $documentResults = collect($documents)
            ->map(
                fn (Document $document): RetrievedDocument =>
                    $this->scoreDocument(
                        $document,
                        $keywords
                    )
            );

        /*
         * ---------------------------------------------------------
         * Score manual knowledge
         * ---------------------------------------------------------
         */

        $manualResults = collect($manualKnowledges)
            ->map(
                fn (ManualKnowledge $knowledge): RetrievedDocument =>
                    $this->scoreManualKnowledge(
                        $knowledge,
                        $keywords
                    )
            );

        /*
         * ---------------------------------------------------------
         * Merge + filter + rank
         * ---------------------------------------------------------
         */

        return $documentResults
            ->merge($manualResults)
            ->filter(
                fn (RetrievedDocument $document): bool =>
                    $document->score >= $this->minimumScore
            )
            ->sortByDesc(
                fn (RetrievedDocument $document): float =>
                    $document->score
            )
            ->take($limit)
            ->values();
    }

    /**
     * Score an uploaded document and extract evidence.
     *
     * @param Collection<int, string> $keywords
     */
    private function scoreDocument(
        Document $document,
        Collection $keywords,
    ): RetrievedDocument {
        $title = mb_strtolower(
            (string) $document->title
        );

        $rawContent = (string) $document->content?->raw_text;

        $body = mb_strtolower(
            $rawContent
        );

        $headingLines = $this->extractHeadingLines(
            $body
        );

        $score = 0;

        foreach ($keywords as $keyword) {
            /*
             * Title match
             */
            if (str_contains($title, $keyword)) {
                $score += $this->titleWeight;
            }

            /*
             * Heading match
             */
            if ($this->matchesAnyHeading(
                $headingLines,
                $keyword
            )) {
                $score += $this->headingWeight;
            }

            /*
             * Body frequency
             */
            $score += substr_count(
                $body,
                $keyword
            ) * $this->bodyWeight;
        }

        /*
         * ---------------------------------------------------------
         * Evidence extraction
         *
         * This is intentionally separated from scoring.
         * Scoring determines whether the document is relevant.
         * EvidenceBuilder determines which exact text should be
         * shown/highlighted to the user.
         * ---------------------------------------------------------
         */

        $highlights = $this->evidenceBuilder->build(
            $rawContent,
            $keywords
        );

        return new RetrievedDocument(
            documentId: $document->id,
            title: $document->title,
            content: $rawContent,
            score: (float) $score,
            highlights: $highlights,
        );
    }

    /**
     * Score manual knowledge and extract evidence.
     *
     * @param Collection<int, string> $keywords
     */
    private function scoreManualKnowledge(
        ManualKnowledge $knowledge,
        Collection $keywords,
    ): RetrievedDocument {
        $title = mb_strtolower(
            (string) $knowledge->title
        );

        $rawContent = (string) $knowledge->content;

        $body = mb_strtolower(
            $rawContent
        );

        $headingLines = $this->extractHeadingLines(
            $body
        );

        $score = 0;

        foreach ($keywords as $keyword) {
            /*
             * Title match
             */
            if (str_contains($title, $keyword)) {
                $score += $this->titleWeight;
            }

            /*
             * Heading match
             */
            if ($this->matchesAnyHeading(
                $headingLines,
                $keyword
            )) {
                $score += $this->headingWeight;
            }

            /*
             * Body frequency
             */
            $score += substr_count(
                $body,
                $keyword
            ) * $this->bodyWeight;
        }

        $highlights = $this->evidenceBuilder->build(
            $rawContent,
            $keywords
        );

        return new RetrievedDocument(
            /*
             * Manual knowledge is not an uploaded Document.
             *
             * We keep its existing ID contract here so the rest
             * of the retrieval pipeline remains compatible.
             */
            documentId: $knowledge->id,
            title: $knowledge->title,
            content: $rawContent,
            score: (float) $score,
            highlights: $highlights,
        );
    }

    /**
     * Determine whether a keyword appears in any detected heading.
     *
     * @param array<int, string> $headingLines
     */
    private function matchesAnyHeading(
        array $headingLines,
        string $keyword,
    ): bool {
        foreach ($headingLines as $line) {
            if (str_contains($line, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extract probable heading lines from plain extracted text.
     *
     * @return array<int, string>
     */
    private function extractHeadingLines(
        string $body
    ): array {
        return collect(
            explode("\n", $body)
        )
            ->map(
                fn (string $line): string =>
                    trim($line)
            )
            ->filter(
                fn (string $line): bool =>
                    $line !== '' &&
                    mb_strlen($line) <= $this->maxHeadingLength
            )
            ->filter(
                fn (string $line): bool =>
                    !str_ends_with($line, '.')
            )
            ->values()
            ->all();
    }
}