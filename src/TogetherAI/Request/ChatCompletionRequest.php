<?php

declare(strict_types=1);

namespace App\TogetherAI\Request;

class ChatCompletionRequest
{
    public function __construct(
        public readonly string $system,
        public readonly string $prompt,
        public readonly string $model = 'meta-llama/Llama-3.3-70B-Instruct-Turbo',
        public array $messages = [],
    ) {}
}