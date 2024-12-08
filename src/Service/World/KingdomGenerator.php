<?php

declare(strict_types=1);

namespace App\Service\World;

use App\Model\Kingdom;
use App\Model\World;
use App\TogetherAI\Request\ChatCompletionRequest;
use App\TogetherAI\TogetherClient;

final class KingdomGenerator
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
        private readonly TownGenerator $townGenerator,
    ) {}

    /**
     * @return array<array-key, Kingdom>
     * @throws \Exception
     */
    public function generate(World $world): array
    {
        $request = new ChatCompletionRequest(self::SYSTEM, "
            Create 3 different kingdoms for a fantasy world.
            For each kingdom generate a description based on the world it's in.
            Describe important leaders, cultures, history of the kingdom.
            
            Output content in the form:
            Kingdom 1 Name: <KINGDOM NAME>
            Kingdom 1 Description: <KINGDOM DESCRIPTION>
            Kingdom 2 Name: <KINGDOM NAME>
            Kingdom 2 Description: <KINGDOM DESCRIPTION>
            Kingdom 3 Name: <KINGDOM NAME>
            Kingdom 3 Description: <KINGDOM DESCRIPTION>
            
            World Name: {$world->name}
            World Description: {$world->description}
        ");

        $kingdoms = $this->parseResponse($this->client->createChatCompletion($request));

        foreach ($kingdoms as $kingdom) {
            $kingdom->world = $world;
            $kingdom->towns = $this->townGenerator->generate($kingdom);
        }

        return $kingdoms;
    }

    /**
     * @return array<array-key, Kingdom>
     * @throws \Exception
     */
    private function parseResponse(string $response): array
    {
        $pattern = '/Kingdom \d+ Name:\s*(.+?)\s*Kingdom \d+ Description:\s*(.+?)(?=\s*Kingdom \d+ Name:|$)/';
        if (preg_match_all($pattern, $response, $matches, PREG_SET_ORDER)) {

            return array_map(static fn (array $match): Kingdom => new Kingdom($match[1], $match[2]), $matches);
        }

        throw new \Exception('Failed to parse the kingdoms response');
    }
}