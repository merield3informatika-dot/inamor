<?php

namespace App\Repositories;

use App\Models\WorkspaceMember;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class WorkspaceMemberRepository
{
    public function create(array $data): WorkspaceMember
    {
        return WorkspaceMember::create($data);
    }

    public function update(
        WorkspaceMember $member,
        array $data,
    ): WorkspaceMember {

        $member->update($data);

        return $member->fresh();
    }

    public function delete(
        WorkspaceMember $member,
    ): bool {

        return (bool) $member->delete();
    }

    public function find(
        int $id,
    ): ?WorkspaceMember {

        return WorkspaceMember::query()
            ->with('user')
            ->find($id);
    }

    public function paginate(
        int $workspaceId,
        int $perPage = 15,
    ): LengthAwarePaginator {

        return WorkspaceMember::query()
            ->with('user')
            ->where('workspace_id', $workspaceId)
            ->orderByDesc('role')
            ->paginate($perPage);
    }

    public function findByWorkspaceAndUser(
        int $workspaceId,
        int $userId,
    ): ?WorkspaceMember {

        return WorkspaceMember::query()
            ->where('workspace_id', $workspaceId)
            ->where('user_id', $userId)
            ->first();
    }
}