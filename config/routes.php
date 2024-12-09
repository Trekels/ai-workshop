<?php

declare(strict_types=1);

use \Slim\App;
use App\Controller as AppController;

/* @var App $app */

$app->map(['GET'], '/', AppController\OverviewController::class);
$app->map(['GET', 'POST'], '/create', AppController\CreateController::class);

$app->map(['GET'], '/explore/{world}', AppController\ExploreController::class);
$app->map(['POST'], '/explore/{world}/message', AppController\ExploreMessageController::class);
