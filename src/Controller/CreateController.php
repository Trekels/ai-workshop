<?php

declare(strict_types=1);

namespace App\Controller;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Twig\Environment;

final class CreateController
{
    public function __construct(
        private Environment $twig,
    ) {}

    public function __invoke(Request $request): Response
    {
        // TODO: Handle the creation of a new world.

        return new Response(body: $this->twig->render('World/create.html.twig'));
    }
}