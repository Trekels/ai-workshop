<?php

declare(strict_types=1);

use App\Controller as AppController;
use League\Container as LeagueContainer;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = new LeagueContainer\Container();
$container->delegate(new LeagueContainer\ReflectionContainer());

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->get('/', AppController\HomeController::class);

$app->run();
