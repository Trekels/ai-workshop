<?php

declare(strict_types=1);

namespace App\Service\World;

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
     * @return array<array-key, array{name: string, description: string, towns: array}>
     * @throws \Exception
     */
    public function generate(string $worldContext): array
    {
        $request = new ChatCompletionRequest(self::SYSTEM, "
            Create 1 different kingdoms for a fantasy world.
            For each kingdom generate a description based on the world it's in.
            Describe important leaders, cultures, history of the kingdom.
            
            Output content in the form:
            Kingdom 1 Name: <KINGDOM NAME>
            Kingdom 1 Description: <KINGDOM DESCRIPTION>
            
            $worldContext
        ");

        $kingdoms = $this->parseResponse($this->client->createChatCompletion($request));

        // Generate the towns for each kingdom, passing the world and specific kingdom info
        return array_map(function (array $kingdom) use ($worldContext) {
            $kingdom['towns'] = $this->townGenerator->generate(
                "Kingdom Name: {$kingdom['name']} \n" .
                "Kingdom Description: {$kingdom['description']} \n" .
                $worldContext
            );

            return $kingdom;
        }, $kingdoms);
    }

    /**
     * @return array<array-key, array{name: string, description: string}>
     * @throws \Exception
     */
    private function parseResponse(string $response): array
    {
        $pattern = '/Kingdom \d+ Name:\s*(.+?)\s*Kingdom \d+ Description:\s*(.+?)(?=\s*Kingdom \d+ Name:|$)/';
        if (preg_match_all($pattern, $response, $matches, PREG_SET_ORDER)) {

            return array_map(static fn (array $match): array => ['name' => $match[1], 'description' => $match[2]], $matches);
        }

        throw new \Exception('Failed to parse the kingdoms response');
    }
}