<?php

namespace App\Services\Knowledge;

use App\Contracts\RetrievalServiceInterface;
use App\DataTransferObjects\KnowledgeAnswer;
use App\Models\User;
use App\Services\AI\AIService;
use App\Services\AI\PromptBuilder;
use App\Services\Workspace\WorkspaceResolver;

final class KnowledgeService
{
    private readonly string $notFoundAnswer;

    public function __construct(
        private readonly RetrievalServiceInterface $retrievalService,
        private readonly ContextBuilder $contextBuilder,
        private readonly PromptBuilder $promptBuilder,
        private readonly AIService $aiService,
        private readonly WorkspaceResolver $workspaceResolver,
        private readonly FeedbackService $feedbackService,
    ) {
        $this->notFoundAnswer = (string) config(
            'knowledge.prompt.fallback_answer',
            'Informasi tidak ditemukan pada dokumen.'
        );
    }

    public function ask(User $user, string $question): KnowledgeAnswer
    {
        $workspaceId = $this->workspaceResolver->resolveId($user);

        $documents = $this->retrievalService->search(
            $workspaceId,
            $question
        );

        /*
        |--------------------------------------------------------------------------
        | Tidak ada dokumen yang relevan
        |--------------------------------------------------------------------------
        */

        if ($documents->isEmpty()) {

            $this->feedbackService->record(
                workspaceId: $workspaceId,
                question: $question,
            );

            return new KnowledgeAnswer(
                answer: $this->notFoundAnswer,
                sources: [],
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Bangun prompt AI
        |--------------------------------------------------------------------------
        */

        $context = $this->contextBuilder->build($documents);

        $prompt = $this->promptBuilder->build(
            $context,
            $question
        );

        $answer = $this->aiService->ask($prompt);

        /*
        |--------------------------------------------------------------------------
        | AI tetap tidak menemukan jawaban walaupun ada dokumen
        |--------------------------------------------------------------------------
        */

        if ($this->isFallbackAnswer($answer)) {

            $this->feedbackService->record(
                workspaceId: $workspaceId,
                question: $question,
            );
        }

        return new KnowledgeAnswer(
            answer: $answer,
            sources: $documents
                ->pluck('title')
                ->unique()
                ->values()
                ->all(),
        );
    }

    private function isFallbackAnswer(string $answer): bool
    {
        return mb_strtolower(trim($answer))
            === mb_strtolower(trim($this->notFoundAnswer));
    }
}