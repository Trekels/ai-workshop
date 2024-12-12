<?php

declare(strict_types=1);

namespace App\TogetherAI;

use GuzzleHttp\Client;

readonly class ClientFactory
{
    public function __construct(private string $apiKey) {}

    public function create(): TogetherClient
    {
        return new TogetherClient(new Client([
            'base_uri' => 'https://api.together.xyz/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ],
        ]), new ResponseParser());
    }
}