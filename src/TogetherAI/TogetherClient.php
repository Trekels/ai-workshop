<?php

declare(strict_types=1);

namespace App\TogetherAI;

use App\TogetherAI\Request\ChatCompletionRequest;
use GuzzleHttp\Client as GuzzleClient;

final readonly class TogetherClient
{
    public function __construct(
        private GuzzleClient $client,
        private ResponseParser $responseParser,
    ) {}

    public function createChatCompletion(ChatCompletionRequest $request): string
    {
        $response = $this->client->request('POST', '< the request endpoint >', [
            'json' => [/* < the request body > */]
        ]);

        return $this->responseParser->parse($response->getBody()->getContents());
    }
}