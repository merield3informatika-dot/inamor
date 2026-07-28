<?php

namespace App\Services\Knowledge;

use App\Models\KnowledgeFeedback;
use App\Models\ManualKnowledge;
use App\Models\User;
use App\Repositories\ManualKnowledgeRepository;
use App\Services\Workspace\WorkspaceResolver;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ManualKnowledgeService
{
    public function __construct(
        protected WorkspaceResolver $workspaceResolver,
        protected ManualKnowledgeRepository $manualKnowledgeRepository,
    ) {
    }

    public function create(
        User $user,
        string $title,
        string $content,
        ?KnowledgeFeedback $feedback = null,
    ): ManualKnowledge {

        return DB::transaction(function () use (
            $user,
            $title,
            $content,
            $feedback
        ) {

            $manualKnowledge = ManualKnowledge::create([
                'workspace_id' => $this->workspaceResolver->resolveId($user),
                'created_by'   => $user->id,
                'title'        => $title,
                'content'      => $content,
                'status'       => 'published',
            ]);

            if ($feedback) {
                $feedback->markAsResolved();
            }

            return $manualKnowledge;
        });
    }

    public function getWorkspaceKnowledge(
        User $user
    ): Collection {

        return $this->manualKnowledgeRepository->getByWorkspace(
            $this->workspaceResolver->resolveId($user)
        );

    }

    public function update(
        ManualKnowledge $knowledge,
        array $data
    ): bool {

        return $this->manualKnowledgeRepository->update(
            $knowledge,
            $data
        );

    }

    public function delete(
        ManualKnowledge $knowledge
    ): bool {

        return $this->manualKnowledgeRepository->delete(
            $knowledge
        );

    }
}