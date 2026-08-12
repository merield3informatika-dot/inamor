<?php

namespace App\Repositories;

use App\Models\ManualKnowledge;
use Illuminate\Database\Eloquent\Collection;

class ManualKnowledgeRepository
{
    /**
     * Create new manual knowledge.
     */
    public function create(array $data): ManualKnowledge
    {
        return ManualKnowledge::create($data);
    }

    /**
     * Search manual knowledge by keywords.
     */
    public function searchKnowledge(
        int $workspaceId,
        array $keywords
    ): Collection {

        $query = ManualKnowledge::query()
            ->where('workspace_id', $workspaceId)
            ->where('status', 'published');

        $query->where(function ($query) use ($keywords) {

            foreach ($keywords as $keyword) {

                $query->orWhere('title', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%");

            }

        });

        return $query->get();
    }

    /**
     * Get all manual knowledge in workspace.
     */
    public function getByWorkspace(
        int $workspaceId
    ): Collection {

        return ManualKnowledge::query()
            ->where('workspace_id', $workspaceId)
            ->latest()
            ->get();

    }

    /**
     * Find specific manual knowledge in workspace.
     */
    public function findByWorkspace(
        int $workspaceId,
        int $knowledgeId
    ): ?ManualKnowledge {

        return ManualKnowledge::query()
            ->where('workspace_id', $workspaceId)
            ->find($knowledgeId);

    }

    /**
     * Update manual knowledge.
     */
    public function update(
        ManualKnowledge $knowledge,
        array $data
    ): bool {

        return $knowledge->update($data);

    }

    /**
     * Delete manual knowledge.
     */
    public function delete(
        ManualKnowledge $knowledge
    ): bool {

        return (bool) $knowledge->delete();

    }
}