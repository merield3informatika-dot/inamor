<?php

namespace App\Services\AI;

use App\Exceptions\AIProviderException;
use Illuminate\Support\Facades\Cache;
use Throwable;

final class ProviderHealthService
{
    /**
     * Status codes that are considered severe enough to trip the
     * circuit breaker for a provider.
     */
    private const CIRCUIT_BREAKER_STATUS_CODES = [429, 401, 500, 502, 503];

    private readonly int $cooldownSeconds;

    public function __construct()
    {
        $this->cooldownSeconds = (int) config(
            'knowledge.router.health_cooldown',
            300
        );
    }

    /**
     * Whether a provider is currently outside its cooldown window.
     */
    public function isHealthy(string $providerKey): bool
    {
        return ! Cache::has($this->cooldownKey($providerKey));
    }

    /**
     * Put a provider into cooldown after a circuit-breaker-worthy failure.
     */
    public function markUnhealthy(string $providerKey): void
    {
        Cache::put(
            $this->cooldownKey($providerKey),
            now()->toDateTimeString(),
            $this->cooldownSeconds
        );
    }

    /**
     * Whether a thrown error should trip the circuit breaker for a provider.
     * Based on the exception's actual status code/type, not string matching.
     */
    public function shouldTriggerCircuitBreaker(Throwable $e): bool
    {
        if (! $e instanceof AIProviderException) {
            return false;
        }

        if ($e->isConnectionError()) {
            return true;
        }

        return $e->statusCode() !== null
            && in_array($e->statusCode(), self::CIRCUIT_BREAKER_STATUS_CODES, true);
    }

    private function cooldownKey(string $providerKey): string
    {
        return "ai_router:unhealthy:{$providerKey}";
    }
}