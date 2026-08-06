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

            return new KnowledgeAnswer(
                answer: $result->memory->knowledge,
                sources: [[
                    'id' => $result->memory->id,
                    'title' => $result->memory->title,
                    'confidence' => $result->confidence,
                    'score' => $result->score,
                ]],
                confidence: $result->confidence,
            );
        }

        $cached = $this->aiCacheService->find($workspaceId, $question);

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
                sources: $cached->sources ?? [],
                confidence: $cached->confidence,
            );
        }

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

        $context = $this->contextBuilder->build($documents);

        $prompt = $this->promptBuilder->build(
            $context,
            $question
        );

        $start = microtime(true);

        try {

            $answer = $this->aiRouterService->ask($prompt);

            $latency = (int) round(
                (microtime(true) - $start) * 1000
            );

            $provider = $this->aiRouterService->lastProvider() ?? 'unknown';
            $model = $this->aiRouterService->lastModel() ?? 'unknown';

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

            $sources = $documents
                ->map(fn ($document) => [
                    'id' => $document->documentId,
                    'title' => $document->title,
                    'confidence' => null,
                    'score' => $document->score,
                ])
                ->unique('id')
                ->values()
                ->all();

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

        if ($this->isFallbackAnswer($answer)) {
            $this->feedbackService->record(
                workspaceId: $workspaceId,
                question: $question,
            );
        }

        return new KnowledgeAnswer(
            answer: $answer,
            sources: $sources,
            confidence: null,
        );
    }

    private function isFallbackAnswer(string $answer): bool
    {
        return mb_strtolower(trim($answer))
            === mb_strtolower(trim($this->notFoundAnswer));
    }
}