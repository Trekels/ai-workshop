<?php

declare(strict_types=1);

namespace App\Controller;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Twig\Environment;

final readonly class HomeController
{
    public function __construct(
        private Environment $twig,
    ) {}

    public function __invoke(Request $request): Response
    {
        return new Response(body: $this->twig->render('index.html.twig'));
    }
}