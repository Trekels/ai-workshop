<?php

declare(strict_types=1);

namespace App\Service\Explore;

use App\TogetherAI\Request\ChatCompletionRequest;
use App\TogetherAI\TogetherClient;

final readonly class StartGenerator
{
    public const string SYSTEM = "
        You are an AI Game master. Your job is to create a 
        start to an adventure based on the world, kingdom, town and character 
        a player is playing as. 
        Instructions:
        You must only use 2-4 sentences
        Write in second person. For example: 'You are Jack' \
        Write in present tense. For example 'You stand at...' \
        First describe the character and their backstory. \
        Then describes where they start and what they see around them.
    ";

    public function __construct(private TogetherClient $client) {}

    /**
     * @param array{ _id: int, name: string, description: string, kingdoms: array } $world
     */
    public function generate(array $world): array
    {
        $worldDesc = $world['description'];
        $kingdomDesc = $world['kingdoms'][0]['description'];
        $townDesc = $world['kingdoms'][0]['towns'][0]['description'];
        $characterDesc = $world['kingdoms'][0]['towns'][0]['characters'][0]['description'];

        $conversationStart = $this->client->createChatCompletion(new ChatCompletionRequest(self::SYSTEM, "

            World: $worldDesc
            Kingdom: $kingdomDesc
            Town: $townDesc
            Character: $characterDesc
        "));

        return [
            'world' => $world['_id'],
            'history' => [
                ['role' => 'bot', 'time' => new \DateTime()->format('H:i'), 'message' => $conversationStart],
            ],
        ];
    }
}