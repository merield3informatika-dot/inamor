<?php

namespace App\Services;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceJoinRequest;
use App\Repositories\WorkspaceJoinRequestRepository;

class WorkspaceJoinRequestService
{
    public function __construct(
        protected WorkspaceJoinRequestRepository $repository,
    ) {
    }

    /**
     * Submit join request.
     */
    public function submit(
        Workspace $workspace,
        User $user,
        array $data,
    ): WorkspaceJoinRequest {

        return $this->repository->create([
            'workspace_id' =>
                $workspace->id,

            'user_id' =>
                $user->id,

            'full_name' =>
                $data['full_name'],

            'identity_card_path' =>
                $data['identity_card_path'],

            'selfie_with_identity_card_path' =>
                $data[
                    'selfie_with_identity_card_path'
                ],

            'status' =>
                'pending',
        ]);
    }


    /**
     * Approve join request.
     */
    public function approve(
        WorkspaceJoinRequest $joinRequest,
        User $reviewer,
    ): WorkspaceJoinRequest {

        return $this->repository->update(
            $joinRequest,
            [
                'status' =>
                    'approved',

                'reviewed_by' =>
                    $reviewer->id,

                'reviewed_at' =>
                    now(),

                'rejection_reason' =>
                    null,
            ],
        );
    }


    /**
     * Reject join request.
     */
    public function reject(
        WorkspaceJoinRequest $joinRequest,
        User $reviewer,
        string $reason,
    ): WorkspaceJoinRequest {

        return $this->repository->update(
            $joinRequest,
            [
                'status' =>
                    'rejected',

                'reviewed_by' =>
                    $reviewer->id,

                'reviewed_at' =>
                    now(),

                'rejection_reason' =>
                    $reason,
            ],
        );
    }


    /**
     * Paginate archived join requests.
     */
    public function paginateArchived(
        int $workspaceId,
    ) {
        return $this->repository
            ->paginateArchived(
                $workspaceId,
            );
    }


    /**
     * Archive join request.
     */
    public function archive(
        WorkspaceJoinRequest $joinRequest,
    ): void {

        $this->repository->delete(
            $joinRequest,
        );
    }


    /**
     * Restore archived join request.
     */
    public function restore(
        int $id,
    ): bool {

        return $this->repository->restore(
            $id,
        );
    }
}