<?php

declare(strict_types=1);

namespace App\Service\World;

use App\TogetherAI\Request\ChatCompletionRequest;
use App\TogetherAI\TogetherClient;

final class TownGenerator
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
        private readonly NPCGenerator $NPCGenerator,
    ) {}

    /**
     * @return array<array-key, array{name: string, description: string, characters: array}>
     * @throws \Exception
     */
    public function generate(string $kingdomContext): array
    {
        $request = new ChatCompletionRequest(self::SYSTEM, "
            Create 2 different towns for a fantasy kingdom world.
            Describe the region it's in, important places of the town,
            and interesting history about it.
            
            Output content in the form:
            Town 1 Name: <TOWN NAME>
            Town 1 Description: <TOWN DESCRIPTION>
            Town 2 Name: <TOWN NAME>
            Town 2 Description: <TOWN DESCRIPTION>
            
            $kingdomContext
        ");

        $towns = $this->parseResponse($this->client->createChatCompletion($request));

        // Generate the characters for each town, passing the world, kingdom and town info
        return array_map(function (array $town) use ($kingdomContext) {
            $town['towns'] = $this->NPCGenerator->generate(
                "Town Name: {$town['name']} \n" .
                "Town Description: {$town['description']} \n" .
                $kingdomContext
            );

            return $town;
        }, $towns);
    }

    /**
     * @return array<array-key, array{name: string, description: string}>
     * @throws \Exception
     */
    private function parseResponse(string $response): array
    {
        $pattern = '/Town \d+ Name:\s*(.+?)\s*Town \d+ Description:\s*(.+?)(?=\s*Town \d+ Name:|$)/';
        if (preg_match_all($pattern, $response, $matches, PREG_SET_ORDER)) {

            return array_map(static fn (array $match): array => ['name' => $match[1], 'description' => $match[2]], $matches);
        }

        throw new \Exception('Failed to parse the towns response');
    }
}