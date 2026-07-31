<?php

namespace App\Services;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use App\Repositories\WorkspaceRepository;
use Illuminate\Support\Str;

class WorkspaceService
{
    public function __construct(
        protected WorkspaceRepository $workspaceRepository
    ) {}

    public function create(User $user, string $name)
    {
        $workspace = $this->workspaceRepository->create([
            'uuid' => Str::uuid(),
            'owner_id' => $user->id,
            'name' => $name,
            'slug' => Str::slug($name),
        ]);

        $workspace->members()->create([
            'user_id' => $user->id,
            'role' => 'admin',
        ]);
        $user->update([
    'current_workspace_id' => $workspace->id,
]);

        return $workspace;
    }

    public function resolveActive(User $user): Workspace
    {
        if ($user->current_workspace_id) {
            $isMember = WorkspaceMember::query()
                ->where('workspace_id', $user->current_workspace_id)
                ->where('user_id', $user->id)
                ->exists();

            if ($isMember) {
                return $user->currentWorkspace;
            }
        }

        $membership = $user->workspaceMemberships()->oldest('id')->first();

        abort_if(! $membership, 403, 'Anda belum tergabung dalam workspace manapun.');

        $user->update(['current_workspace_id' => $membership->workspace_id]);

        return $membership->workspace;
    }

    public function switchTo(User $user, Workspace $workspace): void
    {
        $isMember = WorkspaceMember::query()
            ->where('workspace_id', $workspace->id)
            ->where('user_id', $user->id)
            ->exists();

        abort_unless($isMember, 403, 'Anda tidak memiliki akses ke workspace ini.');

        $user->update(['current_workspace_id' => $workspace->id]);
    }
}