<?php

declare(strict_types=1);

namespace App\Controller;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Twig\Environment;

final class ExploreMessageController
{
    public function __construct(
        private Environment $twig,
    ) {}

    public function __invoke(Request $request): Response
    {
        // TODO Handle message

        return new Response(body: $this->twig->render('Explore/_bot_message.html.twig', [
            'date' => new \DateTimeImmutable(),
            'message' => 'TODO: Implement this',
        ]));
    }
}