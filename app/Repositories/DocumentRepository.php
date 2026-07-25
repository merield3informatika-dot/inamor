<?php

namespace App\Repositories;

use App\Models\Document;
use Illuminate\Database\Eloquent\Collection;

class DocumentRepository
{
    public function create(array $data): Document
    {
        return Document::create($data);
    }

    public function getByWorkspace(int $workspaceId): Collection
    {
        return Document::where('workspace_id', $workspaceId)
            ->latest()
            ->get();
    }
}