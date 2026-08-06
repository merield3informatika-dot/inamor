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
    public function count(): int
    {
        return AIRequest::count();
    }

    /**
     * Total requests today.
     */
    public function countToday(): int
    {
        return AIRequest::query()
            ->whereDate(
                'created_at',
                today()
            )
            ->count();
    }

    /**
     * Count by engine.
     */
    public function countByEngine(string $engine): int
    {
        return AIRequest::query()
            ->where('engine', $engine)
            ->count();
    }

    /**
     * Count by engine today.
     */
    public function countTodayByEngine(string $engine): int
    {
        return AIRequest::query()
            ->where('engine', $engine)
            ->whereDate(
                'created_at',
                today()
            )
            ->count();
    }

    /**
     * Count by status.
     */
    public function countByStatus(string $status): int
    {
        return AIRequest::query()
            ->where('status', $status)
            ->count();
    }

    /**
     * Count by status today.
     */
    public function countTodayByStatus(string $status): int
    {
        return AIRequest::query()
            ->where('status', $status)
            ->whereDate(
                'created_at',
                today()
            )
            ->count();
    }

    /**
     * Average latency.
     */
    public function averageLatency(): ?float
    {
        return AIRequest::query()
            ->whereNotNull('latency')
            ->avg('latency');
    }

    /**
     * Average latency today.
     */
    public function averageLatencyToday(): ?float
    {
        return AIRequest::query()
            ->whereNotNull('latency')
            ->whereDate(
                'created_at',
                today()
            )
            ->avg('latency');
    }

    /**
     * Latest request.
     */
    public function latest(): ?AIRequest
    {
        return AIRequest::query()
            ->latest()
            ->first();
    }

    /**
     * Latest failed request.
     */
    public function latestFailed(): ?AIRequest
    {
        return AIRequest::query()
            ->where('status', 'failed')
            ->latest()
            ->first();
    }

    /**
     * Latest quota exceeded.
     */
    public function latestQuota(): ?AIRequest
    {
        return AIRequest::query()
            ->where('status', 'quota')
            ->latest()
            ->first();
    }

    /**
     * Latest timeout.
     */
    public function latestTimeout(): ?AIRequest
    {
        return AIRequest::query()
            ->where('status', 'timeout')
            ->latest()
            ->first();
    }

    /**
     * Latest requests.
     *
     * @return Collection<int,AIRequest>
     */
    public function latestRequests(
        int $limit = 10,
    ): Collection {

        return AIRequest::query()
            ->latest()
            ->limit($limit)
            ->get();
    }
}