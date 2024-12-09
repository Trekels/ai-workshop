<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ConversationRepository;
use App\Repository\WorldRepository;
use App\Service\Explore\StartGenerator;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Twig\Environment;

final readonly class ExploreController
{
    public function __construct(
        private Environment $twig,
        private StartGenerator $startGenerator,
        private WorldRepository $worldRepository,
        private ConversationRepository $conversationRepository,
    ) {}

    public function __invoke(Request $request): Response
    {
        $worldId = (int) $request->getAttribute('world_id');
        $world = $this->worldRepository->findWorld($worldId);

        if (null === $world) {
            return new Response(404, [], 'World not found');
        }

        $conversation = $this->conversationRepository->findForWorld($worldId);
        if (null === $conversation) {
            $conversation = $this->startGenerator->generate($world);

            $this->conversationRepository->save($conversation);
        }

        return new Response(body: $this->twig->render('World/explore.html.twig', [
            'world' => $world,
            'conversation' => $conversation,
        ]));
    }
}