<?php

namespace App\Services\AI;

use App\Repositories\AI\AIRequestRepository;

final class AIAnalyticsService
{
    public function __construct(
        private readonly AIRequestRepository $repository,
    ) {
    }

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
     * @return array<string,mixed>
     */
    public function statistics(): array
    {
        $todayRequests = $this->repository->countToday();

        $knowledgeMemory = $this->repository
            ->countTodayByEngine('knowledge_memory');

        $manualKnowledge = $this->repository
            ->countTodayByEngine('manual_knowledge');

        $gemini = $this->repository
            ->countTodayByEngine('gemini');

        $quota = $this->repository
            ->countTodayByStatus('quota');

        $timeout = $this->repository
            ->countTodayByStatus('timeout');

        $failed = $this->repository
            ->countTodayByStatus('failed');

        $notFound = $this->repository
            ->countTodayByStatus('not_found');

        $latency = $this->repository
            ->averageLatencyToday();

        return [

            'today_requests' => $todayRequests,

            'provider' => 'Gemini',

            'model' => config('gemini.model'),

            'knowledge_memory' => [

                'count' => $knowledgeMemory,

                'percentage' => $this->percentage(
                    $knowledgeMemory,
                    $todayRequests
                ),

            ],

            'manual_knowledge' => [

                'count' => $manualKnowledge,

                'percentage' => $this->percentage(
                    $manualKnowledge,
                    $todayRequests
                ),

            ],

            'gemini' => [

                'count' => $gemini,

                'percentage' => $this->percentage(
                    $gemini,
                    $todayRequests
                ),

            ],

            'latency' => [

                'average' => $latency
                    ? round($latency)
                    : 0,

                'unit' => 'ms',

            ],

            'errors' => [

                'quota' => $quota,

                'timeout' => $timeout,

                'failed' => $failed,

                'not_found' => $notFound,

                'total' => $quota + $timeout + $failed,

            ],

            'health' => $this->health(
                $quota,
                $timeout,
                $failed
            ),

        ];
    }

    private function percentage(
        int $value,
        int $total,
    ): int {

        if ($total === 0) {
            return 0;
        }

        return (int) round(
            ($value / $total) * 100
        );
    }

    /**
     * @return array<string,string>
     */
    private function health(
        int $quota,
        int $timeout,
        int $failed,
    ): array {

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
}