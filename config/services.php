<?php

declare(strict_types=1);

use App\Repository\ConversationRepository;
use App\Repository\RepositoryFactory;
use App\Repository\WorldRepository;
use App\TogetherAI\ClientFactory;
use App\TogetherAI\TogetherClient;
use League\Container as LeagueContainer;

/** @var LeagueContainer\Container $container */

$container->add(TogetherClient::class, static fn () => new ClientFactory($_ENV['TOGETHER_API_KEY'])->create());

$container->add(RepositoryFactory::class)->addArgument(PROJECT_ROOT . $_ENV['DB_STORAGE_PATH']);

$container->add(WorldRepository::class, function () use ($container) {
    return new WorldRepository($container->get(RepositoryFactory::class)->create(WorldRepository::NAME));
});

$container->add(ConversationRepository::class, function () use ($container) {
    return new ConversationRepository($container->get(RepositoryFactory::class)->create(ConversationRepository::NAME));
});
