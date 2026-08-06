<?php

namespace App\Exceptions;

use RuntimeException;
use Throwable;

final class AIProviderException extends RuntimeException
{
    public function __construct(
        string $message,
        private readonly ?int $statusCode = null,
        private readonly bool $isConnectionError = false,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function statusCode(): ?int
    {
        return $this->statusCode;
    }

    public function isConnectionError(): bool
    {
        return $this->isConnectionError;
    }
}