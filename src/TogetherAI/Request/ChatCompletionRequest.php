<?php

declare(strict_types=1);

namespace App\TogetherAI\Request;

readonly class ChatCompletionRequest
{
    public function __construct(
        public string $system,
        public string $prompt,
        public string $model = 'meta-llama/Llama-3.3-70B-Instruct-Turbo',
    ) {}
}