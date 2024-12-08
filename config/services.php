<?php

declare(strict_types=1);

use App\TogetherAI\ClientFactory;
use App\TogetherAI\TogetherClient;
use League\Container as LeagueContainer;

/** @var LeagueContainer\Container $container */

$container->add(TogetherClient::class, static fn () => new ClientFactory($_ENV['TOGETHER_API_KEY'])->create());
