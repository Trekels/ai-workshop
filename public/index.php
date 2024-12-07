<?php

declare(strict_types=1);

use League\Container as LeagueContainer;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/bootstrap.php';

/** @var LeagueContainer\Container $container */
AppFactory::setContainer($container);
$app = AppFactory::create();

require __DIR__ . '/../config/routes.php';

$app->run();
