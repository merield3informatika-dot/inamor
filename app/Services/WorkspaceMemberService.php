<?php

namespace App\Services;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use App\Repositories\WorkspaceMemberRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

        $member = $this->repository->find($memberId);

        abort_if(! $member, 404);

        $this->repository->delete($member);
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

        $member = $this->repository->find($memberId);

        abort_if(! $member, 404);

        return $this->changeRole(
            $member,
            $role,
        );
    }
}