<?php

namespace App\Repositories;

use App\Models\WorkspaceJoinRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class WorkspaceJoinRequestRepository
{
    public function create(array $data): WorkspaceJoinRequest
    {
        return WorkspaceJoinRequest::create($data);
    }

    public function update(
        WorkspaceJoinRequest $joinRequest,
        array $data,
    ): WorkspaceJoinRequest {

        $joinRequest->update($data);

        return $joinRequest->fresh();
    }

    public function find(
        int $id,
    ): ?WorkspaceJoinRequest {

       return WorkspaceJoinRequest::withTrashed()
    ->find($id);
    }

    public function findPendingByWorkspace(
        int $workspaceId,
    ): Collection {

        return WorkspaceJoinRequest::query()
            ->where('workspace_id', $workspaceId)
            ->where('status', 'pending')
            ->latest()
            ->get();
    }

    public function findPendingByUser(
        int $workspaceId,
        int $userId,
    ): ?WorkspaceJoinRequest {

        return WorkspaceJoinRequest::query()
            ->where('workspace_id', $workspaceId)
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->first();
    }

    public function paginate(
        int $workspaceId,
        int $perPage = 15,
    ): LengthAwarePaginator {

        return WorkspaceJoinRequest::query()
            ->where('workspace_id', $workspaceId)
            ->latest()
            ->paginate($perPage);
    }

   public function delete(
    WorkspaceJoinRequest $joinRequest,
): bool {

    return (bool) $joinRequest->delete();
}
    public function paginateArchived(
    int $workspaceId,
    int $perPage = 15,
): LengthAwarePaginator {

    return WorkspaceJoinRequest::onlyTrashed()
        ->where('workspace_id', $workspaceId)
        ->latest('deleted_at')
        ->paginate($perPage);
}
public function restore(
    int $id,
): bool {

    return WorkspaceJoinRequest::onlyTrashed()
        ->findOrFail($id)
        ->restore();
}
}