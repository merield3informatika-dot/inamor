<?php

namespace App\Services;

use App\Models\User;
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

        return $workspace;
    }
}