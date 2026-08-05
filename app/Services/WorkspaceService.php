<?php

namespace App\Services;

use App\Models\CalendarEvent;
use App\Models\Document;
use App\Models\KnowledgeFeedback;
use App\Models\ManualKnowledge;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use App\Repositories\WorkspaceRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class WorkspaceService
{
    public function __construct(
        protected WorkspaceRepository $workspaceRepository,
        protected WorkspaceInvitationService $workspaceInvitationService,
    ) {
    }

    /**
     * Create new workspace.
     */
    public function create(User $user, array $data): Workspace
    {
        return DB::transaction(function () use ($user, $data) {

            $workspace = $this->workspaceRepository->create([
                'uuid' => Str::uuid(),
                'owner_id' => $user->id,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['description'] ?? null,
                'logo' => $data['logo'] ?? null,
                'visibility' => $data['visibility'] ?? 'private',
            ]);

            $workspace->members()->create([
                'user_id' => $user->id,
                'role' => 'admin',
            ]);

            $user->update([
                'current_workspace_id' => $workspace->id,
            ]);

            $this->workspaceInvitationService->create(
                $workspace,
                $user
            );

            return $workspace;
        });
    }

    /**
     * Update workspace.
     */
    public function update(
        Workspace $workspace,
        array $data
    ): Workspace {
        return $this->workspaceRepository
            ->update($workspace, $data);
    }

    /**
     * Resolve active workspace.
     */
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

        $membership = $user->workspaceMemberships()
            ->oldest('id')
            ->first();

        abort_if(
            ! $membership,
            403,
            'Anda belum tergabung dalam workspace manapun.'
        );

        $user->update([
            'current_workspace_id' => $membership->workspace_id,
        ]);

        return $membership->workspace;
    }

    /**
     * Switch active workspace.
     */
    public function switchTo(
        User $user,
        Workspace $workspace
    ): void {

        $isMember = WorkspaceMember::query()
            ->where('workspace_id', $workspace->id)
            ->where('user_id', $user->id)
            ->exists();

        abort_unless(
            $isMember,
            403,
            'Anda tidak memiliki akses ke workspace ini.'
        );

        $user->update([
            'current_workspace_id' => $workspace->id,
        ]);
    }

    /**
     * Delete active workspace.
     *
     * Return:
     * true  = user still has another workspace
     * false = user has no workspace left
     */
    public function destroy(
        User $user,
        string $password
    ): bool {

        if (! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'Incorrect password.',
            ]);
        }

        $workspace = $this->resolveActive($user);

        abort_unless(
            $workspace->owner_id === $user->id,
            403,
            'Only the Workspace Owner can perform this action.'
        );

        $fallbackMembership = $user->workspaceMemberships()
            ->where('workspace_id', '!=', $workspace->id)
            ->oldest('id')
            ->first();

        DB::transaction(function () use ($workspace) {

            $workspace->documents()->delete();

            $workspace->manualKnowledges()->delete();

            $workspace->knowledgeFeedbacks()->delete();

            $workspace->calendarEvents()->delete();

            $workspace->members()->delete();

            if ($workspace->invitation) {
                $workspace->invitation()->delete();
            }

            $this->workspaceRepository->delete($workspace);
        });

        if ($fallbackMembership) {

            $user->update([
                'current_workspace_id' => $fallbackMembership->workspace_id,
            ]);

            return true;
        }

        $user->update([
            'current_workspace_id' => null,
        ]);

        return false;
    }
    public function leave(User $user): void
{
    $workspace = $this->resolveActive($user);

    abort_if(
        $workspace->owner_id === $user->id,
        403,
        'Workspace owner cannot leave the workspace.'
    );

    WorkspaceMember::query()
        ->where('workspace_id', $workspace->id)
        ->where('user_id', $user->id)
        ->delete();

    $user->update([
        'current_workspace_id' => null,
    ]);
}
}