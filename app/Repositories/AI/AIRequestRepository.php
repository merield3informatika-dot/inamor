<?php

namespace App\Repositories\AI;

use App\Models\AIRequest;
use Illuminate\Support\Collection;

final class AIRequestRepository
{
    /**
     * Store AI request.
     *
     * @param array<string,mixed> $attributes
     */
    public function create(array $attributes): AIRequest
    {
        return AIRequest::create($attributes);
    }

    /**
     * Total requests.
     */
    public function count(int $workspaceId): int
    {
        return AIRequest::query()
            ->where('workspace_id', $workspaceId)
            ->count();
    }

    /**
     * Total requests today.
     */
    public function countToday(int $workspaceId): int
    {
        return AIRequest::query()
            ->where('workspace_id', $workspaceId)
            ->whereDate('created_at', today())
            ->count();
    }

    /**
     * Count by engine.
     */
    public function countByEngine(int $workspaceId, string $engine): int
    {
        return AIRequest::query()
            ->where('workspace_id', $workspaceId)
            ->where('engine', $engine)
            ->count();
    }

    /**
     * Count by engine today.
     */
    public function countTodayByEngine(int $workspaceId, string $engine): int
    {
        return AIRequest::query()
            ->where('workspace_id', $workspaceId)
            ->where('engine', $engine)
            ->whereDate('created_at', today())
            ->count();
    }

    /**
     * Count by status.
     */
    public function countByStatus(int $workspaceId, string $status): int
    {
        return AIRequest::query()
            ->where('workspace_id', $workspaceId)
            ->where('status', $status)
            ->count();
    }

    /**
     * Count by status today.
     */
    public function countTodayByStatus(int $workspaceId, string $status): int
    {
        return AIRequest::query()
            ->where('workspace_id', $workspaceId)
            ->where('status', $status)
            ->whereDate('created_at', today())
            ->count();
    }

    /**
     * Average latency.
     */
    public function averageLatency(int $workspaceId): ?float
    {
        return AIRequest::query()
            ->where('workspace_id', $workspaceId)
            ->whereNotNull('latency')
            ->avg('latency');
    }

    /**
     * Average latency today.
     */
    public function averageLatencyToday(int $workspaceId): ?float
    {
        return AIRequest::query()
            ->where('workspace_id', $workspaceId)
            ->whereNotNull('latency')
            ->whereDate('created_at', today())
            ->avg('latency');
    }

    /**
     * Latest request.
     */
    public function latest(int $workspaceId): ?AIRequest
    {
        return AIRequest::query()
            ->where('workspace_id', $workspaceId)
            ->latest()
            ->first();
    }

    /**
     * Latest failed request.
     */
    public function latestFailed(int $workspaceId): ?AIRequest
    {
        return AIRequest::query()
            ->where('workspace_id', $workspaceId)
            ->where('status', 'failed')
            ->latest()
            ->first();
    }

    /**
     * Latest quota exceeded.
     */
    public function latestQuota(int $workspaceId): ?AIRequest
    {
        return AIRequest::query()
            ->where('workspace_id', $workspaceId)
            ->where('status', 'quota')
            ->latest()
            ->first();
    }

    /**
     * Latest timeout.
     */
    public function latestTimeout(int $workspaceId): ?AIRequest
    {
        return AIRequest::query()
            ->where('workspace_id', $workspaceId)
            ->where('status', 'timeout')
            ->latest()
            ->first();
    }

    /**
     * Latest requests.
     *
     * @return Collection<int,AIRequest>
     */
    public function latestRequests(int $workspaceId, int $limit = 10): Collection
    {
        return AIRequest::query()
            ->where('workspace_id', $workspaceId)
            ->latest()
            ->limit($limit)
            ->get();
    }
}