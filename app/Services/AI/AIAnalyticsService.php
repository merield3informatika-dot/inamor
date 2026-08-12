<?php

namespace App\Services\AI;

use App\Models\AICache;
use App\Models\AIRequest;
use App\Repositories\AI\AIRequestRepository;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

final class AIAnalyticsService
{
    public function __construct(
        private readonly AIRequestRepository $repository,
    ) {
    }

    /**
     * Record one AI execution.
     */
    public function record(
        int $workspaceId,
        int $userId,
        string $provider,
        string $model,
        string $engine,
        string $question,
        string $status,
        int $latency = 0,
        ?string $errorMessage = null,
    ): void {
        $this->repository->create([
            'workspace_id' => $workspaceId,
            'user_id' => $userId,
            'provider' => $provider,
            'model' => $model,
            'engine' => $engine,
            'question' => $question,
            'status' => $status,
            'latency' => $latency,
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Build real analytics for one workspace.
     *
     * Nothing is fabricated here.
     * If the database does not contain enough information,
     * the result is null / zero / no_data.
     *
     * @return array<string,mixed>
     */
    public function statistics(int $workspaceId): array
    {
        $now = now();

        /*
        |--------------------------------------------------------------------------
        | Base query
        |--------------------------------------------------------------------------
        */

        $baseQuery = AIRequest::query()
            ->where('workspace_id', $workspaceId);

        /*
        |--------------------------------------------------------------------------
        | Basic request statistics
        |--------------------------------------------------------------------------
        */

        $todayRequests = (clone $baseQuery)
            ->whereDate('created_at', $now->toDateString())
            ->count();

        $monthRequests = (clone $baseQuery)
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        $successfulRequests = (clone $baseQuery)
            ->where('status', 'success')
            ->count();

        $failedRequests = (clone $baseQuery)
            ->where('status', 'failed')
            ->count();

        $quotaRequests = (clone $baseQuery)
            ->where('status', 'quota')
            ->count();

        $timeoutRequests = (clone $baseQuery)
            ->where('status', 'timeout')
            ->count();

        $notFoundRequests = (clone $baseQuery)
            ->where('status', 'not_found')
            ->count();

        $cacheHits = (clone $baseQuery)
            ->where('status', 'cache_hit')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Latency
        |--------------------------------------------------------------------------
        */

        $avgResponseMs = (int) round(
            (float) ((clone $baseQuery)
                ->where('latency', '>', 0)
                ->avg('latency') ?? 0)
        );

        $todayAvgResponseMs = (int) round(
            (float) ((clone $baseQuery)
                ->whereDate('created_at', $now->toDateString())
                ->where('latency', '>', 0)
                ->avg('latency') ?? 0)
        );

        /*
        |--------------------------------------------------------------------------
        | Provider statistics
        |--------------------------------------------------------------------------
        */

        $providerKeys = [
            'gemini',
            'groq',
            'openrouter',
        ];

        $providers = [];

        foreach ($providerKeys as $provider) {
            $providers[$provider] = $this->providerStatistics(
                $workspaceId,
                $provider
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Cache statistics
        |--------------------------------------------------------------------------
        */

        $cacheQuery = AICache::query()
            ->where('workspace_id', $workspaceId);

        $cacheSize = (clone $cacheQuery)->count();

        $cacheHitCount = (clone $cacheQuery)->sum('hit_count');

        /*
        |--------------------------------------------------------------------------
        | Most cached questions
        |--------------------------------------------------------------------------
        */

        $mostCached = (clone $cacheQuery)
            ->where('hit_count', '>', 0)
            ->orderByDesc('hit_count')
            ->limit(5)
            ->get()
            ->map(fn (AICache $cache): array => [
                'question' => $cache->normalized_question
                    ?: $cache->question_hash,
                'hit_count' => (int) $cache->hit_count,
            ])
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | Top questions
        |--------------------------------------------------------------------------
        */

        $topQuestions = (clone $baseQuery)
            ->whereNotNull('question')
            ->selectRaw('question, COUNT(*) as request_count, MAX(created_at) as last_asked')
            ->groupBy('question')
            ->orderByDesc('request_count')
            ->limit(10)
            ->get()
            ->map(function ($item): array {
                return [
                    'question' => $item->question,
                    'count' => (int) $item->request_count,
                    'provider' => $this->questionProvider(
                        $item->question
                    ),
                    'date' => $item->last_asked
                        ? Carbon::parse($item->last_asked)->diffForHumans()
                        : '—',
                ];
            })
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | Recent activity
        |--------------------------------------------------------------------------
        */

        $timeline = $this->recentTimeline($workspaceId);

        /*
        |--------------------------------------------------------------------------
        | Seven-day request chart
        |--------------------------------------------------------------------------
        */

        $performance = $this->performance(
            $workspaceId
        );

        /*
        |--------------------------------------------------------------------------
        | Knowledge Memory
        |--------------------------------------------------------------------------
        |
        | KnowledgeService records:
        |
        | engine = knowledge_memory
        | status = success
        |
        */

        $knowledgeMemoryTotal = (clone $baseQuery)
            ->where('engine', 'knowledge_memory')
            ->count();

        $knowledgeMemorySuccess = (clone $baseQuery)
            ->where('engine', 'knowledge_memory')
            ->where('status', 'success')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Retrieval
        |--------------------------------------------------------------------------
        |
        | KnowledgeService records retrieval failures / misses through
        | the "knowledge" engine and not_found status.
        |
        */

        $retrievalTotal = (clone $baseQuery)
            ->where('engine', 'knowledge')
            ->count();

        $retrievalSuccess = (clone $baseQuery)
            ->where('engine', 'knowledge')
            ->where('status', 'success')
            ->count();

        $retrievalNotFound = (clone $baseQuery)
            ->where('engine', 'knowledge')
            ->where('status', 'not_found')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Real provider counts
        |--------------------------------------------------------------------------
        */

        $activeProviders = collect($providers)
            ->filter(fn (array $provider): bool =>
                $provider['request'] > 0
            )
            ->count();

        $mostUsedProvider = collect($providers)
            ->sortByDesc('request')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Health
        |--------------------------------------------------------------------------
        */

        $health = $this->health(
            total: $todayRequests,
            failed: (clone $baseQuery)
                ->whereDate('created_at', $now->toDateString())
                ->where('status', 'failed')
                ->count(),
            timeout: (clone $baseQuery)
                ->whereDate('created_at', $now->toDateString())
                ->where('status', 'timeout')
                ->count(),
            quota: (clone $baseQuery)
                ->whereDate('created_at', $now->toDateString())
                ->where('status', 'quota')
                ->count(),
        );

        /*
        |--------------------------------------------------------------------------
        | Pipeline
        |--------------------------------------------------------------------------
        |
        | Only expose pipeline stages that we can prove from ai_requests.
        |
        */

        $pipeline = [
            $this->pipelineNode(
                key: 'knowledge_memory',
                label: 'Knowledge Memory',
                total: $knowledgeMemoryTotal,
                success: $knowledgeMemorySuccess,
                avgMs: $this->averageLatencyForEngine(
                    $workspaceId,
                    'knowledge_memory'
                ),
            ),

            $this->pipelineNode(
                key: 'ai_cache',
                label: 'AI Cache',
                total: $cacheHits,
                success: $cacheHits,
                avgMs: 0,
                hit: $cacheHits,
            ),

            $this->pipelineNode(
                key: 'retrieval',
                label: 'Retrieval',
                total: $retrievalTotal,
                success: $retrievalSuccess,
                avgMs: $this->averageLatencyForEngine(
                    $workspaceId,
                    'knowledge'
                ),
                miss: $retrievalNotFound,
            ),
        ];

        /*
        |--------------------------------------------------------------------------
        | Return
        |--------------------------------------------------------------------------
        */

        return [
            'overview' => [
                'health' => $health,

                'requests_today' => $todayRequests,

                'requests_month' => $monthRequests,

                'total_requests' => $baseQuery->count(),

                'successful_requests' => $successfulRequests,

                'failed_requests' => $failedRequests,

                'success_rate' => $this->percentage(
                    $successfulRequests,
                    max(
                        1,
                        $successfulRequests
                        + $failedRequests
                        + $quotaRequests
                        + $timeoutRequests
                    )
                ),

                'avg_response_ms' => $avgResponseMs,

                'today_avg_response_ms' => $todayAvgResponseMs,

                /*
                 * Tidak ada data telemetry terpisah untuk tahap ini.
                 */
                'avg_retrieval_ms' => null,
                'avg_prompt_ms' => null,
                'avg_ai_ms' => $avgResponseMs,

                /*
                 * Jangan mengarang coverage.
                 */
                'knowledge_coverage_pct' => null,

                /*
                 * Cache hit yang benar-benar tercatat.
                 */
                'ai_saved_pct' => $this->percentage(
                    $cacheHits,
                    max(1, $todayRequests)
                ),

                'cost_today' => null,
                'cost_month' => null,

                'active_providers' => $activeProviders,

                'router_mode' => null,

                'active_provider' => $mostUsedProvider['name'] ?? null,
            ],

            /*
            |--------------------------------------------------------------------------
            | Pipeline
            |--------------------------------------------------------------------------
            */

            'pipeline' => [
                'nodes' => $pipeline,
            ],

            /*
            |--------------------------------------------------------------------------
            | Router
            |--------------------------------------------------------------------------
            */

            'router' => [
                'mode' => null,
                'active_provider' => $mostUsedProvider['name'] ?? null,
                'provider_order' => [],
                'switch_count' => null,
                'fallback_count' => null,
                'circuit_breaker_count' => null,
                'cooldown_seconds' => null,
                'last_switch_at' => null,
            ],

            /*
            |--------------------------------------------------------------------------
            | Providers
            |--------------------------------------------------------------------------
            */

            'providers' => $providers,

            /*
            |--------------------------------------------------------------------------
            | Knowledge Memory
            |--------------------------------------------------------------------------
            */

            'knowledge_memory' => [
                'hit' => $knowledgeMemorySuccess,

                'miss' => max(
                    0,
                    $knowledgeMemoryTotal - $knowledgeMemorySuccess
                ),

                'coverage_pct' => $this->percentage(
                    $knowledgeMemorySuccess,
                    $knowledgeMemoryTotal
                ),

                'used_today' => (clone $baseQuery)
                    ->whereDate(
                        'created_at',
                        $now->toDateString()
                    )
                    ->where('engine', 'knowledge_memory')
                    ->count(),

                'saved_count' => $knowledgeMemorySuccess,

                'most_used' => [],

                'unused' => [],
            ],

            /*
            |--------------------------------------------------------------------------
            | Cache
            |--------------------------------------------------------------------------
            */

            'cache' => [
                'hit' => $cacheHits,

                'miss' => null,

                'hit_rate_pct' => $this->percentage(
                    $cacheHits,
                    max(1, $todayRequests)
                ),

                'avg_age' => $this->cacheAverageAge(
                    $cacheQuery->get()
                ),

                'size' => $cacheSize,

                'growth_pct' => null,

                'most_cached' => $mostCached,

                'top_knowledge' => [],
            ],

            /*
            |--------------------------------------------------------------------------
            | Retrieval
            |--------------------------------------------------------------------------
            */

            'retrieval' => [
                'avg_score' => null,

                'avg_search_ms' => $this->averageLatencyForEngine(
                    $workspaceId,
                    'knowledge'
                ),

                'avg_chunk' => null,

                'avg_context' => null,

                'no_match' => $retrievalNotFound,

                'retrieved' => $retrievalSuccess,

                'most_used_document' => null,
            ],

            /*
            |--------------------------------------------------------------------------
            | Prompt
            |--------------------------------------------------------------------------
            |
            | Belum ada telemetry khusus prompt builder.
            |
            */

            'prompt' => [
                'generated' => null,
                'avg_size' => null,
                'avg_context_length' => null,
                'avg_time_ms' => null,
                'longest' => null,
                'shortest' => null,
            ],

            /*
            |--------------------------------------------------------------------------
            | Errors
            |--------------------------------------------------------------------------
            */

            'errors' => [
                'quota' => $quotaRequests,
                'timeout' => $timeoutRequests,
                'failed' => $failedRequests,
                'unauthorized' => null,
                'server_error' => null,
                'circuit_breaker' => null,
                'provider_down' => null,
                'knowledge_not_found' => $notFoundRequests,
                'prompt_failed' => null,
                'retrieval_failed' => null,
            ],

            /*
            |--------------------------------------------------------------------------
            | Cost
            |--------------------------------------------------------------------------
            */

            'cost' => [
                'today' => null,
                'month' => null,

                'most_expensive_provider' => null,

                'most_used_provider' =>
                    $mostUsedProvider['name'] ?? null,

                'avg_cost' => null,

                'ai_saved' => $cacheHits,

                'token_saved' => null,
            ],

            /*
            |--------------------------------------------------------------------------
            | Questions
            |--------------------------------------------------------------------------
            */

            'top_questions' => $topQuestions,

            /*
            |--------------------------------------------------------------------------
            | Knowledge Gap
            |--------------------------------------------------------------------------
            */

            'knowledge_gap' => [],

            /*
            |--------------------------------------------------------------------------
            | Timeline
            |--------------------------------------------------------------------------
            */

            'timeline' => $timeline,

            /*
            |--------------------------------------------------------------------------
            | Performance / Chart
            |--------------------------------------------------------------------------
            */

            'performance' => $performance,

            /*
            |--------------------------------------------------------------------------
            | System Health
            |--------------------------------------------------------------------------
            */

            'system_health' => [
                $this->componentHealth(
                    name: 'Knowledge Memory',
                    total: $knowledgeMemoryTotal,
                    failed: $knowledgeMemoryTotal - $knowledgeMemorySuccess
                ),

                $this->cacheHealth(
                    $cacheSize
                ),

                $this->componentHealth(
                    name: 'Retrieval',
                    total: $retrievalTotal,
                    failed: $retrievalNotFound
                ),

                $this->untrackedComponent(
                    'Prompt Builder'
                ),

                $this->untrackedComponent(
                    'Smart Router'
                ),

                $this->providerHealth(
                    'Gemini',
                    $providers['gemini']
                ),

                $this->providerHealth(
                    'Groq',
                    $providers['groq']
                ),

                $this->providerHealth(
                    'OpenRouter',
                    $providers['openrouter']
                ),

                $this->analyticsHealth(
                    $todayRequests
                ),
            ],
        ];
    }

    /**
     * Provider statistics.
     *
     * @return array<string,mixed>
     */
    private function providerStatistics(
        int $workspaceId,
        string $engine
    ): array {
        $query = AIRequest::query()
            ->where('workspace_id', $workspaceId)
            ->where('engine', $engine);

        $total = (clone $query)->count();

        $success = (clone $query)
            ->where('status', 'success')
            ->count();

        $failed = (clone $query)
            ->where('status', 'failed')
            ->count();

        $quota = (clone $query)
            ->where('status', 'quota')
            ->count();

        $timeout = (clone $query)
            ->where('status', 'timeout')
            ->count();

        $lastRequest = (clone $query)
            ->latest('created_at')
            ->first();

        $lastError = (clone $query)
            ->where('status', 'failed')
            ->latest('created_at')
            ->value('error_message');

        $model = (clone $query)
            ->whereNotNull('model')
            ->latest('created_at')
            ->value('model');

        $status = 'no_data';

        if ($total > 0) {
            if ($failed > 0 || $quota > 0 || $timeout > 0) {
                $status = 'degraded';
            } else {
                $status = 'healthy';
            }
        }

        return [
            'name' => $engine,

            'status' => $status,

            'label' => match ($status) {
                'healthy' => 'Healthy',
                'degraded' => 'Degraded',
                default => 'No Data',
            },

            'model' => $model,

            'request' => $total,

            'success' => $success,

            'failed' => $failed,

            'success_rate' => $this->percentage(
                $success,
                $total
            ),

            'quota' => $quota,

            'timeout' => $timeout,

            'avg_response_ms' => $this->averageLatencyForEngine(
                $workspaceId,
                $engine
            ),

            'avg_token' => null,

            'avg_cost' => null,

            'circuit_breaker' => null,

            'last_used' => $lastRequest?->created_at
                ? $lastRequest->created_at->diffForHumans()
                : '—',

            'last_error' => $lastError,
        ];
    }

    /**
     * Seven day real request performance.
     *
     * @return array<string,mixed>
     */
    private function performance(
        int $workspaceId
    ): array {
        $start = now()
            ->startOfDay()
            ->subDays(6);

        $requests = AIRequest::query()
            ->where('workspace_id', $workspaceId)
            ->where('created_at', '>=', $start)
            ->get([
                'id',
                'engine',
                'status',
                'latency',
                'created_at',
            ]);

        $days = collect(
            CarbonPeriod::create(
                $start->copy()->startOfDay(),
                now()->startOfDay()
            )
        );

        $requestTrend = $days
            ->map(function (Carbon $date) use ($requests): array {
                $day = $date->toDateString();

                $items = $requests->filter(
                    fn (AIRequest $request): bool =>
                        $request->created_at->toDateString() === $day
                );

                return [
                    'date' => $day,
                    'label' => $date->format('D'),
                    'requests' => $items->count(),

                    'success' => $items
                        ->where('status', 'success')
                        ->count(),

                    'failed' => $items
                        ->where('status', 'failed')
                        ->count(),

                    'cache_hits' => $items
                        ->where('status', 'cache_hit')
                        ->count(),

                    'latency' => $this->averageCollectionLatency(
                        $items
                    ),
                ];
            })
            ->values()
            ->all();

        $providerTrend = $requests
            ->groupBy('engine')
            ->map(fn (Collection $items, string $engine): array => [
                'provider' => $engine,
                'requests' => $items->count(),
                'success' => $items
                    ->where('status', 'success')
                    ->count(),
                'failed' => $items
                    ->where('status', 'failed')
                    ->count(),
                'avg_latency' => $this->averageCollectionLatency(
                    $items
                ),
            ])
            ->values()
            ->all();

        return [
            'requests' => $requestTrend,

            'latency' => $requestTrend,

            'provider' => $providerTrend,

            'cache' => $requestTrend,

            'knowledge' => $requestTrend,

            'router' => [],
        ];
    }

    /**
     * Recent real activity.
     *
     * @return array<int,array<string,mixed>>
     */
    private function recentTimeline(
        int $workspaceId
    ): array {
        return AIRequest::query()
            ->where('workspace_id', $workspaceId)
            ->latest('created_at')
            ->limit(15)
            ->get()
            ->map(function (AIRequest $request): array {
                $type = match ($request->status) {
                    'success' => 'success',
                    'cache_hit' => 'cache',
                    'not_found' => 'warning',
                    'quota',
                    'timeout',
                    'failed' => 'error',
                    default => 'info',
                };

                return [
                    'type' => $type,

                    'label' => match ($request->status) {
                        'success' => 'AI Request Success',
                        'cache_hit' => 'Cache Hit',
                        'not_found' => 'Knowledge Not Found',
                        'failed' => 'AI Request Failed',
                        'quota' => 'Quota Exceeded',
                        'timeout' => 'Request Timeout',
                        default => 'AI Request',
                    },

                    'detail' => $request->question,

                    'engine' => $request->engine,

                    'model' => $request->model,

                    'latency' => $request->latency,

                    'time' => $request->created_at?->diffForHumans() ?? '—',
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Build pipeline node.
     *
     * @return array<string,mixed>
     */
    private function pipelineNode(
        string $key,
        string $label,
        int $total,
        int $success,
        int $avgMs = 0,
        int $hit = 0,
        int $miss = 0,
    ): array {
        return [
            'key' => $key,

            'label' => $label,

            'total' => $total,

            'success' => $success,

            'pct' => $this->percentage(
                $success,
                $total
            ),

            'avg_ms' => $avgMs,

            'hit' => $hit,

            'miss' => $miss,

            'available' => $total > 0,
        ];
    }

    private function averageLatencyForEngine(
        int $workspaceId,
        string $engine
    ): int {
        return (int) round(
            (float) (
                AIRequest::query()
                    ->where('workspace_id', $workspaceId)
                    ->where('engine', $engine)
                    ->where('latency', '>', 0)
                    ->avg('latency')
                ?? 0
            )
        );
    }

    private function averageCollectionLatency(
        Collection $items
    ): int {
        $latencies = $items
            ->pluck('latency')
            ->filter(fn ($latency): bool =>
                (int) $latency > 0
            );

        if ($latencies->isEmpty()) {
            return 0;
        }

        return (int) round(
            $latencies->avg()
        );
    }

    /**
     * @param Collection<int,AICache> $caches
     */
    private function cacheAverageAge(
        Collection $caches
    ): string {
        if ($caches->isEmpty()) {
            return '—';
        }

        $ages = $caches
            ->filter(fn (AICache $cache): bool =>
                $cache->created_at !== null
            )
            ->map(fn (AICache $cache): int =>
                $cache->created_at->diffInHours(now())
            );

        if ($ages->isEmpty()) {
            return '—';
        }

        return round($ages->avg(), 1) . 'h';
    }

    /**
     * @return array<string,string|int>
     */
    private function componentHealth(
        string $name,
        int $total,
        int $failed
    ): array {
        if ($total === 0) {
            return [
                'name' => $name,
                'status' => 'no_data',
                'label' => 'No Data',
            ];
        }

        if ($failed > 0) {
            return [
                'name' => $name,
                'status' => 'degraded',
                'label' => 'Degraded',
            ];
        }

        return [
            'name' => $name,
            'status' => 'healthy',
            'label' => 'Healthy',
        ];
    }

    /**
     * @return array<string,string>
     */
    private function cacheHealth(
        int $cacheSize
    ): array {
        return [
            'name' => 'AI Cache',

            'status' => $cacheSize > 0
                ? 'healthy'
                : 'no_data',

            'label' => $cacheSize > 0
                ? 'Healthy'
                : 'No Data',
        ];
    }

    /**
     * @return array<string,string>
     */
    private function providerHealth(
        string $name,
        array $provider
    ): array {
        $status = match ($provider['status'] ?? 'no_data') {
            'healthy' => 'healthy',
            'degraded' => 'degraded',
            default => 'no_data',
        };

        return [
            'name' => $name,
            'status' => $status,
            'label' => match ($status) {
                'healthy' => 'Healthy',
                'degraded' => 'Degraded',
                default => 'No Data',
            },
        ];
    }

    /**
     * @return array<string,string>
     */
    private function analyticsHealth(
        int $todayRequests
    ): array {
        return [
            'name' => 'Analytics',

            'status' => $todayRequests > 0
                ? 'healthy'
                : 'no_data',

            'label' => $todayRequests > 0
                ? 'Healthy'
                : 'No Data',
        ];
    }

    /**
     * @return array<string,string>
     */
    private function untrackedComponent(
        string $name
    ): array {
        return [
            'name' => $name,
            'status' => 'no_data',
            'label' => 'No Data',
        ];
    }

    /**
     * @return array<string,string>
     */
    private function health(
        int $total,
        int $failed,
        int $timeout,
        int $quota
    ): array {
        if ($total === 0) {
            return [
                'status' => 'no_data',
                'label' => 'No Data',
                'color' => 'gray',
            ];
        }

        if ($quota > 0) {
            return [
                'status' => 'quota',
                'label' => 'Quota Exceeded',
                'color' => 'yellow',
            ];
        }

        if ($timeout > 0) {
            return [
                'status' => 'timeout',
                'label' => 'Slow Response',
                'color' => 'orange',
            ];
        }

        if ($failed > 0) {
            return [
                'status' => 'failed',
                'label' => 'Service Error',
                'color' => 'red',
            ];
        }

        return [
            'status' => 'healthy',
            'label' => 'Healthy',
            'color' => 'green',
        ];
    }

    private function percentage(
        int $value,
        int $total
    ): int {
        if ($total <= 0) {
            return 0;
        }

        return (int) round(
            ($value / $total) * 100
        );
    }

    private function questionProvider(
        string $question
    ): string {
        return AIRequest::query()
            ->where('question', $question)
            ->latest('created_at')
            ->value('engine')
            ?? '—';
    }
}