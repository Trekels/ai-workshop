<?php

declare(strict_types=1);

namespace App\Service\World;

use App\Model\World;
use App\TogetherAI\Request\ChatCompletionRequest;
use App\TogetherAI\TogetherClient;

final class WorldGenerator
{
    public const string SYSTEM = "
        Your job is to help create interesting fantasy worlds that players would love to play in.

        Instructions:
        - Only generate in plain text without formatting.
        - Use simple clear language without being flowery.
        - You must stay below 3-5 sentences for each description.
    ";

    public function __construct(
        private readonly TogetherClient $client,
        private readonly KingdomGenerator $kingdomGenerator,
    ) {}

    /**
     * @throws \Exception
     */
    public function generate(string $prompt): World
    {
        $request = new ChatCompletionRequest(self::SYSTEM, "
            $prompt

            Output content in the form:
            World Name: <WORLD NAME>
            World Description: <WORLD DESCRIPTION>
        ");

        $world = $this->parseResponse($this->client->createChatCompletion($request));

        $world->kingdoms = $this->kingdomGenerator->generate($world);

        return $world;
    }

    /**
     * @throws \Exception
     */
    private function parseResponse(string $response): World
    {
        $matches = [];
        if (preg_match('/World Name:\s*(.+?)\s*World Description:\s*(.+)/', $response, $matches)) {
            return new World($matches[1], $matches[2]);
        }

        throw new \Exception('Failed to parse the world response');
    }
}