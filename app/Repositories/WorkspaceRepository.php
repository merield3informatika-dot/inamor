<?php

namespace App\Repositories;

use App\Models\Workspace;

class WorkspaceRepository
{
    public function create(array $data): Workspace
    {
        return Workspace::create($data);
    }

    public function update(Workspace $workspace, array $data): Workspace
    {
        $workspace->update($data);

        return $workspace->fresh();
    }

    public function delete(Workspace $workspace): void
    {
        $workspace->delete();
    }
}