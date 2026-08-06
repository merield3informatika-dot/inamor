<?php

namespace App\Services\KnowledgeMemory;

use App\Models\KnowledgeMemory;
use App\Repositories\KnowledgeMemory\KnowledgeMemoryAliasRepository;
use App\Repositories\KnowledgeMemory\KnowledgeMemoryRepository;
use Illuminate\Support\Collection;

final class KnowledgeMemoryService
{
    public function __construct(
        private readonly KnowledgeMemoryRepository $memoryRepository,
        private readonly KnowledgeMemoryAliasRepository $aliasRepository,
    ) {
    }

    /**
     * Create new knowledge memory.
     *
     * @param array<string, mixed> $memory
     * @param array<int, string> $aliases
     */
    public function create(
        array $memory,
        array $aliases = [],
    ): KnowledgeMemory {

        $knowledge = $this->memoryRepository->create($memory);

        if (! empty($aliases)) {

            $this->aliasRepository->createMany(
                $knowledge->id,
                array_unique($aliases),
            );

        }

        return $this->memoryRepository->find(
            $knowledge->id
        );
    }

    /**
     * Update knowledge memory.
     *
     * @param array<string, mixed> $data
     */
    public function update(
        KnowledgeMemory $memory,
        array $data,
    ): KnowledgeMemory {

        return $this->memoryRepository->update(
            $memory,
            $data,
        );
    }

    /**
     * Delete knowledge memory and aliases.
     */
    public function delete(
        KnowledgeMemory $memory,
    ): void {

        $this->aliasRepository->deleteByMemory(
            $memory->id,
        );

        $this->memoryRepository->delete(
            $memory,
        );
    }

    /**
     * Replace aliases.
     *
     * @param array<int, string> $aliases
     */
    public function replaceAliases(
        KnowledgeMemory $memory,
        array $aliases,
    ): void {

        $this->aliasRepository->deleteByMemory(
            $memory->id,
        );

        $this->aliasRepository->createMany(
            $memory->id,
            array_unique($aliases),
        );
    }

    /**
     * Find memory.
     */
    public function find(
        int $id,
    ): ?KnowledgeMemory {

        return $this->memoryRepository->find(
            $id,
        );
    }

    /**
     * Get all workspace memories.
     */
    public function byWorkspace(
        int $workspaceId,
    ): Collection {

        return $this->memoryRepository->byWorkspace(
            $workspaceId,
        );
    }

    /**
     * Delete all memories from a source.
     */
    public function deleteBySource(
        int $workspaceId,
        string $sourceType,
        int $sourceId,
    ): void {

        $this->memoryRepository->deleteBySource(
            $workspaceId,
            $sourceType,
            $sourceId,
        );
    }
}