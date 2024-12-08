<?php

declare(strict_types=1);

namespace App\Service\World;

use App\Model\Town;
use App\Model\Villager;
use App\TogetherAI\Request\ChatCompletionRequest;
use App\TogetherAI\TogetherClient;

final class NPCGenerator
{
    public const string SYSTEM = "
        Your job is to help create interesting fantasy worlds that players would love to play in.
        Instructions:
        - Only generate in plain text without formatting.
        - Use simple clear language without being flowery.
        - You must stay below 3-5 sentences for each description.
    ";

    public function __construct(private readonly TogetherClient $client) {}

    /**
     * @return array<array-key, Villager>
     */
    public function generate(Town $town): array
    {
        $request = new ChatCompletionRequest(self::SYSTEM, "
            Create 3 different characters based on the world, kingdom
            and town they're in. Describe the character's appearance and
            profession, as well as their deeper pains and desires.
    
            Output content in the form:
            Character 1 Name: <CHARACTER NAME>
            Character 1 Description: <CHARACTER DESCRIPTION>
            Character 2 Name: <CHARACTER NAME>
            Character 2 Description: <CHARACTER DESCRIPTION>
            Character 3 Name: <CHARACTER NAME>
            Character 3 Description: <CHARACTER DESCRIPTION>
    
            World Name: {$town->kingdom?->world?->name}
            World Description: {$town->kingdom?->world?->description}
    
            Kingdom Name: {$town->kingdom?->name}
            Kingdom Description: {$town->kingdom?->description}
    
            Town Name: {$town->name}
            Town Description: {$town->description}
        ");

        $villagers = $this->parseResponse($this->client->createChatCompletion($request));

        foreach ($villagers as $villager) {
            $villager->town = $town;
        }

        return $villagers;
    }

    /**
     * @return array<array-key, Villager>
     * @throws \Exception
     */
    private function parseResponse(string $response): array
    {
        $pattern = '/Character \d+ Name:\s*(.+?)\s*Character \d+ Description:\s*(.+?)(?=\s*Character \d+ Name:|$)/';
        if (preg_match_all($pattern, $response, $matches, PREG_SET_ORDER)) {

            return array_map(static fn (array $match): Villager => new Villager($match[1], $match[2]), $matches);
        }

        throw new \Exception('Failed to parse the NPCs response');
    }
}