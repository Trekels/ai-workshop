<?php

declare(strict_types=1);

namespace App\Service\World;

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
     * @return array<array-key, array{name: string, description: string, characters: array}>
     * @throws \Exception
     */
    public function generate(string $townContext): array
    {
        $request = new ChatCompletionRequest(self::SYSTEM, "
            Create 2 different characters based on the world, kingdom
            and town they're in. Describe the character's appearance and
            profession, as well as their deeper pains and desires.
    
            Output content in the form:
            Character 1 Name: <CHARACTER NAME>
            Character 1 Description: <CHARACTER DESCRIPTION>
            Character 2 Name: <CHARACTER NAME>
            Character 2 Description: <CHARACTER DESCRIPTION>
    
            $townContext 
        ");

        return $this->parseResponse($this->client->createChatCompletion($request));
    }

    /**
     * @return array<array-key, array{name: string, description: string}>
     * @throws \Exception
     */
    private function parseResponse(string $response): array
    {
        $pattern = '/Character \d+ Name:\s*(.+?)\s*Character \d+ Description:\s*(.+?)(?=\s*Character \d+ Name:|$)/';
        if (preg_match_all($pattern, $response, $matches, PREG_SET_ORDER)) {

            return array_map(static fn (array $match): array => ['name' => $match[1], 'descriptions' => $match[2]], $matches);
        }

        throw new \Exception('Failed to parse the NPCs response');
    }
}