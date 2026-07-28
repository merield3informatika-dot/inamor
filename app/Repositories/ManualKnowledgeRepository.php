<?php

namespace App\Repositories;

use App\Models\ManualKnowledge;
use Illuminate\Database\Eloquent\Collection;

class ManualKnowledgeRepository
{
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

    public function getByWorkspace(
        int $workspaceId
    ): Collection {

        return ManualKnowledge::query()
            ->where('workspace_id', $workspaceId)
            ->latest()
            ->get();

    }

    public function findByWorkspace(
        int $workspaceId,
        int $knowledgeId
    ): ?ManualKnowledge {

        return ManualKnowledge::query()
            ->where('workspace_id', $workspaceId)
            ->find($knowledgeId);

    }

    public function update(
        ManualKnowledge $knowledge,
        array $data
    ): bool {

        return $knowledge->update($data);

    }

    public function delete(
        ManualKnowledge $knowledge
    ): bool {

        return (bool) $knowledge->delete();

    }
}