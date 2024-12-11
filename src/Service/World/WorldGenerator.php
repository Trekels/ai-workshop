<?php

declare(strict_types=1);

namespace App\Service\World;

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
    ) {}

    /**
     * @return array{ name: string, description: string, kingdoms: array }
     * @throws \Exception
     */
    public function generate(string $prompt): array
    {
        return ['TODO'];
    }

    /**
     * @return array{name: string, description: string}
     * @throws \Exception
     */
    private function parseResponse(string $response): array
    {
        $matches = [];
        if (preg_match('/World Name:\s*(.+?)\s*World Description:\s*(.+)/', $response, $matches)) {
            return ['name' => $matches[1], 'description' => $matches[2]];
        }

        throw new \Exception('Failed to parse the world response');
    }
}