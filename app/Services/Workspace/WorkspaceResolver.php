<?php

namespace App\Services\Workspace;

use App\Models\User;
use App\Models\Workspace;
use RuntimeException;

final class WorkspaceResolver
{
    public function resolve(User $user): Workspace
    {
        if ($user->currentWorkspace) {
            return $user->currentWorkspace;
        }

        $membership = $user->workspaceMemberships()->oldest('id')->first();

        if (! $membership) {
            throw new RuntimeException(sprintf(
                'User [%d] does not belong to any workspace.',
                $user->id
            ));
        }

        $user->update([
            'current_workspace_id' => $membership->workspace_id,
        ]);

        return $membership->workspace;
    }

    public function resolveId(User $user): int
    {
        return $this->resolve($user)->id;
    }
}