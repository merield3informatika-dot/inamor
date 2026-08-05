<?php

namespace App\Repositories;

use App\Models\WorkspaceInvitation;

class WorkspaceInvitationRepository
{
    /**
     * Create invitation.
     */
    public function create(array $data): WorkspaceInvitation
    {
        return WorkspaceInvitation::create($data);
    }

    /**
     * Update invitation.
     */
    public function update(
        WorkspaceInvitation $invitation,
        array $data,
    ): WorkspaceInvitation {

        $invitation->update($data);

        return $invitation->fresh();
    }

    /**
     * Find invitation by workspace.
     */
    public function findByWorkspace(
        int $workspaceId,
    ): ?WorkspaceInvitation {

        return WorkspaceInvitation::query()
            ->where('workspace_id', $workspaceId)
            ->first();
    }

    /**
     * Find invitation by token.
     */
    public function findByToken(
        string $token,
    ): ?WorkspaceInvitation {

        return WorkspaceInvitation::query()
            ->where('token', $token)
            ->first();
    }
}