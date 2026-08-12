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
        protected ManualKnowledgeSyncService $syncService,
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
            // Menggunakan Repository Pattern, BUKAN memanggil ManualKnowledge::create
            $manualKnowledge = $this->manualKnowledgeRepository->create([
                'workspace_id' => $this->workspaceResolver->resolveId($user),
                'created_by'   => $user->id,
                'title'        => $title,
                'content'      => $content,
                'status'       => 'published',
            ]);

            if ($feedback) {
                $feedback->markAsResolved();
            }

            // Jalankan sinkronisasi ke KnowledgeMemory
            $this->syncService->sync($manualKnowledge);

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

        return DB::transaction(function () use ($knowledge, $data) {
            // Simpan perubahan utama
            $updated = $this->manualKnowledgeRepository->update(
                $knowledge,
                $data
            );

            // Sinkronisasi data terbaru (fresh) ke KnowledgeMemory
            $this->syncService->sync($knowledge->fresh());

            return $updated;
        });
    }

    public function delete(
        ManualKnowledge $knowledge
    ): bool {

        return DB::transaction(function () use ($knowledge) {
            // Hapus dari KnowledgeMemory dan Alias terlebih dahulu
            $this->syncService->remove($knowledge);

            // Hapus dari ManualKnowledge
            return $this->manualKnowledgeRepository->delete(
                $knowledge
            );
        });
    }
}