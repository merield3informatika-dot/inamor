<?php

namespace App\Exceptions\Document;

use RuntimeException;
use Throwable;

final class ExtractionFailedException extends RuntimeException
{
    public function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}