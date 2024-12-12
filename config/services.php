<?php

declare(strict_types=1);

use App\TogetherAI\ClientFactory;
use App\TogetherAI\TogetherClient;
use League\Container as LeagueContainer;

/** @var LeagueContainer\Container $container */

$container->add(TogetherClient::class, static fn () => (
    new ClientFactory('d04b74da6099c4379dc658ce435524c230d6d71fa215281ad1a5991c2e369c04')
)->create());
