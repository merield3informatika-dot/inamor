<?php

namespace App\Services\AI;

use App\Models\AICache;
use App\Repositories\AI\AICacheRepository;
use App\Services\Retrieval\QuestionNormalizer;

final class AICacheService
{
    public function __construct(
        private readonly AICacheRepository $repository,
        private readonly QuestionNormalizer $questionNormalizer,
    ) {
    }

    /**
     * Find a cached answer for the given question, registering a hit if found.
     */
    public function find(int $workspaceId, string $question): ?AICache
    {
        [, $hash] = $this->prepare($question);

        $cache = $this->repository->find($workspaceId, $hash);

        if ($cache === null) {
            return null;
        }

        $this->repository->registerHit($cache);

        return $cache;
    }

    /**
     * Store (or refresh) the cached answer for the given question.
     *
     * @param array<int,array<string,mixed>> $sources
     */
    public function store(
        int $workspaceId,
        string $question,
        string $answer,
        string $provider,
        string $model,
        array $sources = [],
        ?float $confidence = null,
    ): AICache {

        [$normalized, $hash] = $this->prepare($question);

        return $this->repository->store([

            'workspace_id' => $workspaceId,

            'question_hash' => $hash,

            'normalized_question' => $normalized,

            'answer' => $answer,

            'sources' => $sources,

            'confidence' => $confidence,

            'provider' => $provider,

            'model' => $model,

            'expires_at' => null,

        ]);
    }

    /**
     * Normalize the question and derive its hash. Single source of truth
     * for both operations so normalization/hashing never drifts apart.
     *
     * @return array{0: string, 1: string} [$normalizedQuestion, $questionHash]
     */
    private function prepare(string $question): array
    {
        $normalized = $this->questionNormalizer->normalize($question);

        return [$normalized, md5($normalized)];
    }
}