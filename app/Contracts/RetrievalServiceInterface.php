<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface RetrievalServiceInterface
{
    /**
     * Find the most relevant documents for a given question within a workspace.
     *
     * When $limit is null, the implementation falls back to its own
     * configured default (see config/knowledge.php: retrieval.result_limit).
     *
     * @return Collection<int, \App\DataTransferObjects\RetrievedDocument>
     */
    public function search(int $workspaceId, string $question, ?int $limit = null): Collection;
}
