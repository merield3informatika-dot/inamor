<?php

namespace App\Services\AI;

use App\Repositories\AI\AIRequestRepository;
use App\Services\AI\Providers\AIProviderInterface;
use App\Services\AI\Providers\GeminiProvider;
use App\Services\AI\Providers\GroqProvider;
use App\Services\AI\Providers\OpenRouterProvider;
use RuntimeException;

final class AIRouterService
{
    private const MODE_PRIORITY = 'priority';

    private const MODE_LATENCY = 'latency';

    private const MODE_HEALTH = 'health';

    /**
     * @var array<string, AIProviderInterface>
     */
    private readonly array $providers;

    /**
     * @var string[]
     */
    private readonly array $priorityOrder;

    private readonly string $mode;

    private ?string $lastProvider = null;

    private ?string $lastModel = null;

    public function __construct(
        GeminiProvider $geminiProvider,
        GroqProvider $groqProvider,
        OpenRouterProvider $openRouterProvider,
        private readonly ProviderHealthService $providerHealthService,
        private readonly AIRequestRepository $aiRequestRepository,
    ) {
        $this->providers = [

            'gemini' => $geminiProvider,

            'groq' => $groqProvider,

            'openrouter' => $openRouterProvider,

        ];

        $this->priorityOrder = (array) config(
            'knowledge.router.provider_order',
            config('knowledge.providers.order', ['gemini', 'groq', 'openrouter'])
        );

        $this->mode = (string) config(
            'knowledge.router.mode',
            self::MODE_PRIORITY
        );
    }

    public function ask(string $prompt): string
    {
        $errors = [];

        foreach ($this->resolveOrder() as $providerKey) {

            if (! isset($this->providers[$providerKey])) {
                continue;
            }

            $provider = $this->providers[$providerKey];

            try {

                $answer = $provider->ask($prompt);

                $this->lastProvider = $provider->name();

                $this->lastModel = $provider->model();

                return $answer;

            } catch (\Throwable $e) {

                if ($this->providerHealthService->shouldTriggerCircuitBreaker($e)) {
                    $this->providerHealthService->markUnhealthy($providerKey);
                }

                $errors[] = sprintf(
                    '[%s] %s',
                    $providerKey,
                    $e->getMessage()
                );

                continue;

            }
        }

        throw new RuntimeException(
            'All AI providers failed: ' . implode(' | ', $errors)
        );
    }

    public function lastProvider(): ?string
    {
        return $this->lastProvider;
    }

    public function lastModel(): ?string
    {
        return $this->lastModel;
    }

    /**
     * Resolve provider order based on the configured router mode.
     * Every mode respects the circuit breaker: unhealthy (cooldown)
     * providers are pushed to the end, never silently skipped forever.
     *
     * @return string[]
     */
    private function resolveOrder(): array
    {
        $baseOrder = match ($this->mode) {
            self::MODE_LATENCY => $this->orderByLatency(),
            default => $this->priorityOrder, // priority & health
        };

        return $this->applyHealthFilter($baseOrder);
    }

    /**
     * @return string[]
     */
    private function orderByLatency(): array
    {
        $latencies = [];

        foreach ($this->priorityOrder as $providerKey) {

            if (! isset($this->providers[$providerKey])) {
                continue;
            }

            $average = $this->aiRequestRepository->averageLatencyByProvider(
                $this->providers[$providerKey]->name()
            );

            // Providers without latency history yet are treated as
            // slowest, so they're still tried last instead of skipped.
            $latencies[$providerKey] = $average ?? PHP_FLOAT_MAX;

        }

        asort($latencies);

        return array_keys($latencies);
    }

    /**
     * Push providers currently in circuit-breaker cooldown to the end
     * of the given order. If every provider is unhealthy, fall back to
     * the original order rather than giving up without trying anything.
     *
     * @param string[] $order
     * @return string[]
     */
    private function applyHealthFilter(array $order): array
    {
        $healthy = [];

        $unhealthy = [];

        foreach ($order as $providerKey) {

            if (! isset($this->providers[$providerKey])) {
                continue;
            }

            if ($this->providerHealthService->isHealthy($providerKey)) {
                $healthy[] = $providerKey;
            } else {
                $unhealthy[] = $providerKey;
            }

        }

        return $healthy !== []
            ? [...$healthy, ...$unhealthy]
            : $order;
    }
}