<?php

namespace App\Repositories\KnowledgeMemory;

use App\Models\KnowledgeMemoryAlias;
use Illuminate\Support\Collection;

final class KnowledgeMemoryAliasRepository
{
    /**
     * Create new alias.
     */
    public function create(array $data): KnowledgeMemoryAlias
    {
        return KnowledgeMemoryAlias::create($data);
    }

    /**
     * Create multiple aliases.
     *
     * @param array<int, string> $aliases
     */
    public function createMany(
        int $knowledgeMemoryId,
        array $aliases,
    ): void {

        foreach ($aliases as $alias) {

            KnowledgeMemoryAlias::create([
                'knowledge_memory_id' => $knowledgeMemoryId,
                'alias' => trim($alias),
            ]);

        }
    }

    /**
     * Get aliases by knowledge memory.
     */
    public function byMemory(
        int $knowledgeMemoryId,
    ): Collection {

        return KnowledgeMemoryAlias::query()
            ->where('knowledge_memory_id', $knowledgeMemoryId)
            ->orderBy('alias')
            ->get();
    }

    /**
     * Search alias.
     */
    public function search(
        string $keyword,
    ): Collection {

        return KnowledgeMemoryAlias::query()
            ->where('alias', 'like', "%{$keyword}%")
            ->get();
    }

    /**
     * Delete aliases by knowledge memory.
     */
    public function deleteByMemory(
        int $knowledgeMemoryId,
    ): void {

        KnowledgeMemoryAlias::query()
            ->where('knowledge_memory_id', $knowledgeMemoryId)
            ->delete();
    }

    /**
     * Delete single alias.
     */
    public function delete(
        KnowledgeMemoryAlias $alias,
    ): void {

        $alias->delete();
    }
}