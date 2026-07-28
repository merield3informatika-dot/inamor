<?php

namespace App\Services\Knowledge;

use App\Models\KnowledgeFeedback;
use App\Services\Retrieval\QuestionNormalizer;

class FeedbackService
{
    public function __construct(
        private readonly QuestionNormalizer $questionNormalizer,
    ) {}

    /**
     * Record missing knowledge.
     */
    public function record(
        int $workspaceId,
        string $question,
    ): KnowledgeFeedback {
        $normalizedQuestion = $this->questionNormalizer->normalize($question);

        $feedback = KnowledgeFeedback::query()
            ->where('workspace_id', $workspaceId)
            ->where('normalized_question', $normalizedQuestion)
            ->first();

        if ($feedback) {
            $feedback->incrementAskedCount();

            return $feedback->fresh();
        }

        return KnowledgeFeedback::create([
            'workspace_id'        => $workspaceId,
            'question'            => $question,
            'normalized_question' => $normalizedQuestion,
            'asked_count'         => 1,
            'status'              => 'pending',
        ]);
    }

    /**
     * Mark a feedback as resolved.
     */
    public function resolve(KnowledgeFeedback $feedback): KnowledgeFeedback
    {
        $feedback->markAsResolved();

        return $feedback->fresh();
    }
}