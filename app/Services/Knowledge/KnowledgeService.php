<?php

namespace App\Services\Knowledge;

use App\Contracts\RetrievalServiceInterface;
use App\DataTransferObjects\KnowledgeAnswer;
use App\Models\User;
use App\Services\AI\AIAnalyticsService;
use App\Services\AI\AICacheService;
use App\Services\AI\AIRouterService;
use App\Services\AI\PromptBuilder;
use App\Services\KnowledgeMemory\KnowledgeSearchService;
use App\Services\Workspace\WorkspaceResolver;

final class KnowledgeService
{
    private readonly string $notFoundAnswer;

    public function __construct(
        private readonly RetrievalServiceInterface $retrievalService,
        private readonly ContextBuilder $contextBuilder,
        private readonly PromptBuilder $promptBuilder,
        private readonly AIRouterService $aiRouterService,
        private readonly AICacheService $aiCacheService,
        private readonly WorkspaceResolver $workspaceResolver,
        private readonly FeedbackService $feedbackService,
        private readonly KnowledgeSearchService $knowledgeSearchService,
        private readonly AIAnalyticsService $analyticsService,
    ) {
        $this->notFoundAnswer = (string) config(
            'knowledge.prompt.fallback_answer',
            'Informasi tidak ditemukan pada dokumen.'
        );
    }

    public function ask(User $user, string $question): KnowledgeAnswer
    {
        $workspaceId = $this->workspaceResolver->resolveId($user);

        /*
        |--------------------------------------------------------------------------
        | 1. Direct Knowledge Memory Search
        |--------------------------------------------------------------------------
        */

        $result = $this->knowledgeSearchService->search(
            $workspaceId,
            $question
        );

        if ($result !== null) {
            $this->analyticsService->record(
                workspaceId: $workspaceId,
                userId: $user->id,
                provider: 'internal',
                model: 'knowledge-memory',
                engine: 'knowledge_memory',
                question: $question,
                status: 'success',
            );

            /*
             * KnowledgeMemory ID != Document ID.
             *
             * Example:
             * memory.id       = 60
             * memory.source_id = 12
             *
             * Preview needs the actual Document ID.
             */
            $documentId = null;

            if (
                $result->memory->source_type === 'document'
                && $result->memory->source_id !== null
            ) {
                $documentId = (int) $result->memory->source_id;
            }

            return new KnowledgeAnswer(
                answer: $result->memory->knowledge,
                sources: [[
                    'document_id' => $documentId,
                    'title' => $result->memory->title,
                    'confidence' => $result->confidence,
                    'score' => $result->score,
                    'highlight' => $result->highlight,
                    'page' => $result->pageNumber,
                    'highlights' => $result->highlight !== ''
                        ? [[
                            'text' => $result->highlight,
                            'page' => $result->pageNumber,
                        ]]
                        : [],
                ]],
                confidence: $result->confidence,
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. AI Cache
        |--------------------------------------------------------------------------
        */

        $cached = $this->aiCacheService->find(
            $workspaceId,
            $question
        );

        if ($cached !== null) {
            $this->analyticsService->record(
                workspaceId: $workspaceId,
                userId: $user->id,
                provider: $cached->provider,
                model: $cached->model,
                engine: 'cache',
                question: $question,
                status: 'cache_hit',
            );

            if ($this->isFallbackAnswer($cached->answer)) {
                $this->feedbackService->record(
                    workspaceId: $workspaceId,
                    question: $question,
                );
            }

            return new KnowledgeAnswer(
                answer: $cached->answer,
                sources: $this->normalizeSources(
                    $cached->sources ?? []
                ),
                confidence: $cached->confidence,
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Document Retrieval
        |--------------------------------------------------------------------------
        */

        $documents = $this->retrievalService->search(
            $workspaceId,
            $question
        );

        if ($documents->isEmpty()) {
            $this->analyticsService->record(
                workspaceId: $workspaceId,
                userId: $user->id,
                provider: 'internal',
                model: 'retrieval',
                engine: 'knowledge',
                question: $question,
                status: 'not_found',
            );

            $this->feedbackService->record(
                workspaceId: $workspaceId,
                question: $question,
            );

            return new KnowledgeAnswer(
                answer: $this->notFoundAnswer,
                sources: [],
                confidence: null,
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Build AI Context
        |--------------------------------------------------------------------------
        */

        $context = $this->contextBuilder->build(
            $documents
        );

        $prompt = $this->promptBuilder->build(
            $context,
            $question
        );

        $start = microtime(true);

        try {
            /*
            |--------------------------------------------------------------------------
            | 5. Ask AI
            |--------------------------------------------------------------------------
            */

            $answer = $this->aiRouterService->ask(
                $prompt
            );

            $latency = (int) round(
                (microtime(true) - $start) * 1000
            );

            $provider = $this->aiRouterService->lastProvider()
                ?? 'unknown';

            $model = $this->aiRouterService->lastModel()
                ?? 'unknown';

            $this->analyticsService->record(
                workspaceId: $workspaceId,
                userId: $user->id,
                provider: $provider,
                model: $model,
                engine: $provider,
                question: $question,
                status: 'success',
                latency: $latency,
            );

            /*
            |--------------------------------------------------------------------------
            | 6. Build Document Sources
            |--------------------------------------------------------------------------
            |
            | RetrievedDocument already contains evidence generated by
            | RetrievalEvidenceBuilder.
            |
            | We preserve:
            | - document_id
            | - title
            | - score
            | - first highlight
            | - page
            | - all highlights
            */

            $sources = $documents
                ->map(function ($document): array {
                    $highlights = is_array($document->highlights ?? null)
                        ? $document->highlights
                        : [];

                    $firstHighlight = $highlights[0] ?? null;

                    return [
                        'document_id' => (int) $document->documentId,
                        'title' => (string) $document->title,
                        'confidence' => null,
                        'score' => $document->score,

                        'highlight' => is_array($firstHighlight)
                            ? ($firstHighlight['text'] ?? null)
                            : null,

                        'page' => is_array($firstHighlight)
                            ? ($firstHighlight['page'] ?? null)
                            : null,

                        'highlights' => $highlights,
                    ];
                })
                ->filter(
                    fn (array $source): bool =>
                        $source['document_id'] > 0
                )
                ->unique('document_id')
                ->values()
                ->all();

            /*
            |--------------------------------------------------------------------------
            | 7. Store AI Cache
            |--------------------------------------------------------------------------
            */

            $this->aiCacheService->store(
                workspaceId: $workspaceId,
                question: $question,
                answer: $answer,
                provider: $provider,
                model: $model,
                sources: $sources,
                confidence: null,
            );
        } catch (\Throwable $e) {
            $latency = (int) round(
                (microtime(true) - $start) * 1000
            );

            $this->analyticsService->record(
                workspaceId: $workspaceId,
                userId: $user->id,
                provider: 'multi',
                model: 'multi',
                engine: 'multi',
                question: $question,
                status: 'failed',
                latency: $latency,
                errorMessage: $e->getMessage(),
            );

            throw $e;
        }

        /*
        |--------------------------------------------------------------------------
        | 8. Fallback Feedback
        |--------------------------------------------------------------------------
        */

        if ($this->isFallbackAnswer($answer)) {
            $this->feedbackService->record(
                workspaceId: $workspaceId,
                question: $question,
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 9. Final Answer
        |--------------------------------------------------------------------------
        */

        return new KnowledgeAnswer(
            answer: $answer,
            sources: $sources,
            confidence: null,
        );
    }

    /**
     * Normalize cached sources so old and new cache formats
     * can coexist safely.
     *
     * @param array<int, mixed> $sources
     * @return array<int, array<string, mixed>>
     */
    private function normalizeSources(array $sources): array
    {
        return collect($sources)
            ->map(function ($source): ?array {
                if (!is_array($source)) {
                    return null;
                }

                /*
                 * New source contract.
                 */
                $documentId = $source['document_id'] ?? null;

                /*
                 * Backward compatibility with old cache:
                 *
                 * 'id' previously represented document ID
                 * in the retrieval branch.
                 */
                if (
                    $documentId === null
                    && isset($source['id'])
                ) {
                    $documentId = $source['id'];
                }

                if (
                    $documentId === null
                    || !is_numeric($documentId)
                    || (int) $documentId <= 0
                ) {
                    return null;
                }

                $highlights = [];

                if (
                    isset($source['highlights'])
                    && is_array($source['highlights'])
                ) {
                    $highlights = collect($source['highlights'])
                        ->filter(
                            fn ($highlight): bool =>
                                is_array($highlight)
                                && isset($highlight['text'])
                        )
                        ->map(
                            fn (array $highlight): array => [
                                'text' => (string) $highlight['text'],
                                'page' => isset($highlight['page'])
                                    ? (
                                        is_numeric($highlight['page'])
                                            ? (int) $highlight['page']
                                            : null
                                    )
                                    : null,
                            ]
                        )
                        ->values()
                        ->all();
                }

                /*
                 * Backward compatibility:
                 *
                 * Old cache only has:
                 * highlight + page
                 */
                if (
                    empty($highlights)
                    && !empty($source['highlight'])
                ) {
                    $highlights[] = [
                        'text' => (string) $source['highlight'],
                        'page' => isset($source['page'])
                            ? (
                                is_numeric($source['page'])
                                    ? (int) $source['page']
                                    : null
                            )
                            : null,
                    ];
                }

                return [
                    'document_id' => (int) $documentId,
                    'title' => (string) ($source['title'] ?? ''),
                    'confidence' => $source['confidence'] ?? null,
                    'score' => $source['score'] ?? null,

                    'highlight' => $source['highlight']
                        ?? ($highlights[0]['text'] ?? null),

                    'page' => $source['page']
                        ?? ($highlights[0]['page'] ?? null),

                    'highlights' => $highlights,
                ];
            })
            ->filter()
            ->unique('document_id')
            ->values()
            ->all();
    }

    private function isFallbackAnswer(
        string $answer
    ): bool {
        return mb_strtolower(trim($answer))
            === mb_strtolower(trim($this->notFoundAnswer));
    }
}