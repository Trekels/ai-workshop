<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\World\WorldGenerator;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Twig\Environment;

final readonly class HomeController
{
    public function __construct(
        private Environment $twig,
        private WorldGenerator $worldGenerator,
    ) {}

    public function __invoke(Request $request): Response
    {
        $world = $this->worldGenerator->generate('
            Generate a creative description for a unique fantasy world with an
            interesting concept around cities build on the backs of massive beasts.
        ');

        return new Response(body: $this->twig->render('index.html.twig'));
    }
}