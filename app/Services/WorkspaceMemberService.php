<?php

namespace App\Services;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use App\Repositories\WorkspaceMemberRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class WorkspaceMemberService
{
    public function __construct(
        protected WorkspaceMemberRepository $repository,
    ) {
    }

    public function paginate(
        User $user,
    ): LengthAwarePaginator {

        return $this->repository->paginate(
            $user->current_workspace_id,
        );
    }

    public function join(
        Workspace $workspace,
        User $user,
        string $role = 'member',
    ): WorkspaceMember {

        $existing = $this->repository
            ->findByWorkspaceAndUser(
                $workspace->id,
                $user->id,
            );

        if ($existing) {
            return $existing;
        }

        return $this->repository->create([
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'role' => $role,
        ]);
    }

    public function leave(
        Workspace $workspace,
        User $user,
    ): void {

        $member = $this->repository
            ->findByWorkspaceAndUser(
                $workspace->id,
                $user->id,
            );

        if (! $member) {
            return;
        }

        $this->repository->delete($member);
    }

    public function remove(
        User $user,
        int $memberId,
    ): void {

        $member = $this->memberManagedBy($user, $memberId);

        DB::transaction(function () use ($member): void {
            $this->repository->delete($member);

            if ($member->user->current_workspace_id !== $member->workspace_id) {
                return;
            }

            $fallbackMembership = $member->user->workspaceMemberships
                ->firstWhere('workspace_id', '!=', $member->workspace_id);

            $member->user->update([
                'current_workspace_id' => $fallbackMembership?->workspace_id,
            ]);
        });
    }

    public function isMember(
        Workspace $workspace,
        User $user,
    ): bool {

        return $this->repository
            ->findByWorkspaceAndUser(
                $workspace->id,
                $user->id,
            ) !== null;
    }

    public function changeRole(
        WorkspaceMember $member,
        string $role,
    ): WorkspaceMember {

        return $this->repository->update(
            $member,
            [
                'role' => $role,
            ]
        );
    }

    public function updateRole(
        User $user,
        int $memberId,
        string $role,
    ): WorkspaceMember {

        $member = $this->memberManagedBy($user, $memberId);

        return $this->changeRole(
            $member,
            $role,
        );
    }

    private function memberManagedBy(
        User $user,
        int $memberId,
    ): WorkspaceMember {

        abort_if(! $user->current_workspace_id, 403);

        $member = $this->repository->findForWorkspace(
            $memberId,
            $user->current_workspace_id,
        );

        abort_if(! $member, 404);

        abort_unless(
            $member->workspace->owner_id === $user->id,
            403,
            'Only the Workspace Owner can manage members.'
        );

        abort_if(
            $member->workspace->owner_id === $member->user_id,
            403,
            'The Workspace Owner cannot be managed as a member.'
        );

        return $member;
    }
}
