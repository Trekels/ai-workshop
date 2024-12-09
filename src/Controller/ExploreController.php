<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\WorldRepository;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Twig\Environment;

final class ExploreController
{
    public function __construct(
        private Environment $twig,
        private WorldRepository $worldRepository,
    ) {}

    public function __invoke(Request $request): Response
    {
        $worldId = $request->getAttribute('world_id');
        $world = $this->worldRepository->findWorld((int) $worldId);

        // TODO: Load conversation history

        return new Response(body: $this->twig->render('World/explore.html.twig', [
            'world' => $world,
        ]));
    }
}