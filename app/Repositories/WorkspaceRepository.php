<?php

namespace App\Repositories;

use App\Models\Workspace;

class WorkspaceRepository
{
    public function create(array $data): Workspace
    {
        return Workspace::create($data);
    }
}