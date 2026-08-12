<?php

namespace App\Repositories\KnowledgeMemory;

use App\Models\KnowledgeMemory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class KnowledgeMemoryRepository
{
    /**
     * Create new knowledge memory.
     */
    public function create(array $data): KnowledgeMemory
    {
        return KnowledgeMemory::create($data);
    }

    /**
     * Update knowledge memory.
     */
    public function update(
        KnowledgeMemory $memory,
        array $data,
    ): KnowledgeMemory {

        $memory->update($data);

        return $memory->fresh();
    }

    /**
     * Delete knowledge memory.
     */
    public function delete(
        KnowledgeMemory $memory,
    ): void {

        $memory->delete();
    }

    /**
     * Find by id.
     */
    public function find(
        int $id,
    ): ?KnowledgeMemory {

        return KnowledgeMemory::query()
            ->with('aliases')
            ->find($id);
    }

    /**
     * Find specific memory by source.
     */
    public function findBySource(
        int $workspaceId,
        string $sourceType,
        int $sourceId,
    ): ?KnowledgeMemory {

        return KnowledgeMemory::query()
            ->where('workspace_id', $workspaceId)
            ->where('source_type', $sourceType)
            ->where('source_id', $sourceId)
            ->first();
    }

    /**
     * Get all memories by source.
     */
    public function getBySource(
        int $workspaceId,
        string $sourceType,
        int $sourceId,
    ): Collection {

        return KnowledgeMemory::query()
            ->where('workspace_id', $workspaceId)
            ->where('source_type', $sourceType)
            ->where('source_id', $sourceId)
            ->get();
    }

    /**
     * Get all memories in workspace.
     */
    public function byWorkspace(
        int $workspaceId,
    ): Collection {

        return KnowledgeMemory::query()
            ->where('workspace_id', $workspaceId)
            ->with('aliases')
            ->latest()
            ->get();
    }

    /**
     * Search by title.
     */
    public function searchTitle(
        int $workspaceId,
        string $keyword,
    ): Collection {

        return KnowledgeMemory::query()
            ->where('workspace_id', $workspaceId)
            ->where('title', 'like', "%{$keyword}%")
            ->with('aliases')
            ->get();
    }

    /**
     * Search by knowledge.
     */
    public function searchKnowledge(
        int $workspaceId,
        string $keyword,
    ): Collection {

        return KnowledgeMemory::query()
            ->where('workspace_id', $workspaceId)
            ->where('knowledge', 'like', "%{$keyword}%")
            ->with('aliases')
            ->get();
    }

    /**
     * Delete all memories from a source.
     */
    public function deleteBySource(
        int $workspaceId,
        string $sourceType,
        int $sourceId,
    ): void {

        KnowledgeMemory::query()
            ->where('workspace_id', $workspaceId)
            ->where('source_type', $sourceType)
            ->where('source_id', $sourceId)
            ->delete();
    }

    /**
     * Search memories (Legacy/Fallback).
     */
    public function search(
        int $workspaceId,
        string $keyword,
    ): Collection {

        return KnowledgeMemory::query()
            ->where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->where(function ($query) use ($keyword) {
                $query
                    ->where('title', 'like', "%{$keyword}%")
                    ->orWhere('knowledge', 'like', "%{$keyword}%");
            })
            ->with('aliases')
            ->get();
    }

    /**
     * Mengambil dokumen secara instan jika NLP Query sama persis dengan Alias.
     * Menggunakan exact match ('=') yang memanfaatkan index database dengan maksimal.
     */
    public function findExactAliasMatch(
        int $workspaceId,
        string $normalizedQuery
    ): ?KnowledgeMemory {

        return KnowledgeMemory::query()
            ->where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->whereHas('aliases', function ($query) use ($normalizedQuery) {
                $query->where('alias', $normalizedQuery);
            })
            ->with('aliases')
            ->first();
    }

    /**
     * Menarik kandidat dokumen yang mengandung setidaknya 1 keyword.
     * Logika bisnis, validasi false-positive, dan pemeringkatan BUKAN dilakukan di sini.
     *
     * @param array<int,string> $keywords
     */
    public function searchByKeywords(
        int $workspaceId,
        array $keywords,
        int $limit = 500
    ): Collection {

        if (empty($keywords)) {
            return collect();
        }

        return KnowledgeMemory::query()
            ->where('workspace_id', $workspaceId)
            ->where('status', 'active')
            ->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->orWhere('title', 'like', "%{$keyword}%")
                          ->orWhere('knowledge', 'like', "%{$keyword}%");

                    $query->orWhereExists(function ($subQuery) use ($keyword) {
                        $subQuery->select(DB::raw(1))
                                 ->from('knowledge_memory_aliases')
                                 ->whereColumn('knowledge_memory_aliases.knowledge_memory_id', 'knowledge_memories.id')
                                 ->where('knowledge_memory_aliases.alias', 'like', "%{$keyword}%");
                    });
                }
            })
            ->with('aliases')
            ->limit($limit) // Mencegah Out of Memory (OOM)
            ->get();
    }
}