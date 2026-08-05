<?php

namespace App\Services;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceInvitation;
use App\Repositories\WorkspaceInvitationRepository;
use Illuminate\Support\Str;

class WorkspaceInvitationService
{
    public function __construct(
        protected WorkspaceInvitationRepository $workspaceInvitationRepository,
    ) {
    }

    /**
     * Create invitation for workspace.
     *
     * One Workspace = One Invitation.
     */
    public function create(
        Workspace $workspace,
        User $user,
    ): WorkspaceInvitation {

        $invitation = $this->workspaceInvitationRepository
            ->findByWorkspace($workspace->id);

        if ($invitation) {
            return $invitation;
        }

        return $this->workspaceInvitationRepository->create([
            'workspace_id' => $workspace->id,
            'created_by' => $user->id,
            'token' => Str::random(64),
        ]);
    }

    /**
     * Find invitation by token.
     */
    public function findByToken(
        string $token,
    ): ?WorkspaceInvitation {

        return $this->workspaceInvitationRepository
            ->findByToken($token);
    }

    /**
     * Regenerate invitation token.
     */
    public function regenerate(
        WorkspaceInvitation $invitation,
    ): WorkspaceInvitation {

        return $this->workspaceInvitationRepository->update(
            $invitation,
            [
                'token' => Str::random(64),
            ]
        );
    }

    /**
     * Increase invitation usage.
     */
    public function touch(
        WorkspaceInvitation $invitation,
    ): WorkspaceInvitation {

        return $this->workspaceInvitationRepository->update(
            $invitation,
            [
                'usage_count' => $invitation->usage_count + 1,
                'last_used_at' => now(),
            ]
        );
    }

    /**
     * Check invitation expiration.
     */
    public function isExpired(
        WorkspaceInvitation $invitation,
    ): bool {

        if ($invitation->expires_at === null) {
            return false;
        }

        return now()->greaterThan(
            $invitation->expires_at);
    }
}