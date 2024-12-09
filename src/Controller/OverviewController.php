<?php

declare(strict_types=1);

namespace App\Controller;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Twig\Environment;

final readonly class OverviewController
{
    public function __construct(
        private Environment $twig,
    ) {}

    public function __invoke(Request $request): Response
    {
        // TODO: Fetch available worlds form the db

        return new Response(body: $this->twig->render('World/overview.html.twig', [
            'worlds' => [],
        ]));
    }
}