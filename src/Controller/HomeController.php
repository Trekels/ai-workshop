<?php

namespace App\Controller;

use GuzzleHttp\Psr7\Response;

final readonly class HomeController
{
    public function __invoke(): Response
    {
        return new Response(body: 'Hello from controller');
    }
}