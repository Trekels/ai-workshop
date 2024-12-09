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
        $response = $this->client->request('POST', 'chat/completions', [
            'json' => [
                "model" => $request->model,
                "messages" => $request->getMessages(),
                "max_tokens" => null,                                                         # | < ------------------
                "temperature" => 1,                                                           # |  These can be moved
                "top_p" => 0.7,                                                               # |  to a config object
                "top_k" => 50,                                                                # |
                "repetition_penalty" => 1,                                                    # |
                "stop" => ["<|eot_id|>","<|eom_id|>"],                                        # | -------------------->
                "stream" => true,
            ],
        ]);

        return $this->responseParser->parse($response->getBody()->getContents());
    }
}