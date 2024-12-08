<?php

declare(strict_types=1);

namespace App\Service\World;

use App\Model\Kingdom;
use App\Model\Town;
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
     * @return array<array-key, Town>
     * @throws \Exception
     */
    public function generate(Kingdom $kingdom): array
    {
        $request = new ChatCompletionRequest(self::SYSTEM, "
            Create 3 different towns for a fantasy kingdom world.
            Describe the region it's in, important places of the town,
            and interesting history about it.
            
            Output content in the form:
            Town 1 Name: <TOWN NAME>
            Town 1 Description: <TOWN DESCRIPTION>
            Town 2 Name: <TOWN NAME>
            Town 2 Description: <TOWN DESCRIPTION>
            Town 3 Name: <TOWN NAME>
            Town 3 Description: <TOWN DESCRIPTION>
            
            World Name: {$kingdom->world?->name}
            World Description: {$kingdom->world?->description}
            
            Kingdom Name: {$kingdom->name}
            Kingdom Description {$kingdom->description}
        ");

        $towns = $this->parseResponse($this->client->createChatCompletion($request));

        foreach ($towns as $town) {
            $town->kingdom = $kingdom;
            $town->villagers = $this->NPCGenerator->generate($town);
        }

        return $towns;
    }

    /**
     * @return array<array-key, Town>
     * @throws \Exception
     */
    private function parseResponse(string $response): array
    {
        $pattern = '/Town \d+ Name:\s*(.+?)\s*Town \d+ Description:\s*(.+?)(?=\s*Town \d+ Name:|$)/';
        if (preg_match_all($pattern, $response, $matches, PREG_SET_ORDER)) {

            return array_map(static fn (array $match): Town => new Town($match[1], $match[2]), $matches);
        }

        throw new \Exception('Failed to parse the towns response');
    }
}