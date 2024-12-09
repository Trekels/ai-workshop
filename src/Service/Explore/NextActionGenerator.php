<?php

declare(strict_types=1);

namespace App\Service\Explore;

use App\TogetherAI\Request\ChatCompletionRequest;
use App\TogetherAI\TogetherClient;

final readonly class NextActionGenerator
{
    public const string SYSTEM = "
        You are an AI Game master. Your job is to write what
        happens next in a player's adventure game.
        Instructions:
        You must on only write 1-3 sentences in response.
        Always write in second person present tense.
        Ex. (You look north and see...)
     ";

    public function __construct(private TogetherClient $client) {}

    /**
     * @param array{ world: int, context: string, history: array{ role: string, time: string, message: string }} $conversation
     */
    public function generate(array $conversation): array
    {
        $request = new ChatCompletionRequest(self::SYSTEM, $conversation['context']);

        foreach ($conversation['history'] as $message) {
            $request->addMessage($message['role'], $message['message']);
        }

        return [
            'role' => 'assistant',
            'time' => new \DateTime()->format('H:i'),
            'message' => $this->client->createChatCompletion($request),
        ];
    }
}