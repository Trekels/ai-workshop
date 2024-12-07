<?php

declare(strict_types=1);

use \Slim\App;
use App\Controller as AppController;

/* @var App $app */

$app->map(['GET', 'POST'], '/', AppController\HomeController::class);
