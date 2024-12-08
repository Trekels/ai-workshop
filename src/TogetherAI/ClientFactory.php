<?php

declare(strict_types=1);

namespace App\TogetherAI;

use GuzzleHttp\Client;

readonly class ClientFactory
{
    public function __construct(private string $apiKey) {}

    public function create(): TogetherClient
    {
        return new TogetherClient(/*< create a new client with default options >*/);
    }
}