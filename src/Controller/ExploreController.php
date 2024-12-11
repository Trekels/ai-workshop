<?php

declare(strict_types=1);

namespace App\Controller;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Twig\Environment;

final class ExploreController
{
    public function __construct(
        private Environment $twig,
    ) {}

    public function __invoke(Request $request): Response
    {
        $world = $request->getAttribute('world_id');

        // TODO: Load correct world and conversation history

        return new Response(body: $this->twig->render('World/explore.html.twig', [
            'world' => $world,
            // 'conversation' => $conversation,
        ]));
    }
}