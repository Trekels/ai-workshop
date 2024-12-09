<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ConversationRepository;
use App\Service\Explore\NextActionGenerator;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Twig\Environment;

final class ExploreMessageController
{
    public function __construct(
        private Environment $twig,
        private NextActionGenerator $nextActionGenerator,
        private ConversationRepository $conversationRepository,
    ) {}

    public function __invoke(Request $request): Response
    {
        $worldId = (int) $request->getAttribute('world_id');
        $conversation = $this->conversationRepository->findForWorld($worldId);

        $message = json_decode($request->getBody()->getContents(), true)['message'];

        $conversation['history'][] = ['role' => 'user', 'time' => new \DateTime()->format('H:i'), 'message' => $message];

        $message = $this->nextActionGenerator->generate($conversation);

        $conversation['history'][] = $message;

        $this->conversationRepository->save($conversation);

        return new Response(body: $this->twig->render('World/includes/_assistant_message.html.twig', [
            'message' => $message,
        ]));
    }
}