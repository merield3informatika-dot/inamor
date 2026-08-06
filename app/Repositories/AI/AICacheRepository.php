<?php

namespace App\Repositories\AI;

use App\Models\AICache;

final class AICacheRepository
{
    /**
     * Find a valid (not expired) cache entry by workspace and question hash.
     */
    public function find(int $workspaceId, string $questionHash): ?AICache
    {
        return AICache::query()
            ->where('workspace_id', $workspaceId)
            ->where('question_hash', $questionHash)
            ->where(function ($query) {

                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());

            })
            ->first();
    }

    /**
     * Store or refresh a cache entry, keyed by workspace and question hash.
     *
     * @param array<string,mixed> $attributes
     */
    public function store(array $attributes): AICache
    {
        return AICache::query()->updateOrCreate(
            [
                'workspace_id' => $attributes['workspace_id'],
                'question_hash' => $attributes['question_hash'],
            ],
            [
                'normalized_question' => $attributes['normalized_question'],
                'answer' => $attributes['answer'],
                'sources' => $attributes['sources'],
                'confidence' => $attributes['confidence'],
                'provider' => $attributes['provider'],
                'model' => $attributes['model'],
                'expires_at' => $attributes['expires_at'],
            ]
        );
    }

    /**
     * Register a cache hit (increment counter and update last hit
     * timestamp in a single query).
     */
    public function registerHit(AICache $cache): void
    {
        $cache->increment('hit_count', 1, [
            'last_hit_at' => now(),
        ]);
    }
}