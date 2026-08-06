<?php

namespace App\Services\AI\Providers;

interface AIProviderInterface
{
    public function name(): string;

    public function model(): string;

    public function ask(string $prompt): string;
}