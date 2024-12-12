<?php

declare(strict_types=1);

use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormRenderer;
use League\Container as LeagueContainer;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\FormFactoryInterface;

// Move up one directory and load the .env file
//$dotenv = Dotenv\Dotenv::createImmutable(PROJECT_ROOT);
//$dotenv->load();

// Instantiate the dependency container and enable autowiring trough reflection
$container = new LeagueContainer\Container();
$container->delegate(new LeagueContainer\ReflectionContainer());

// Load and configure twig templating engine with the symfony forms component for easy form handling
$container->add(Twig\Environment::class, function () {
    $twig = new Twig\Environment(new Twig\Loader\FilesystemLoader([
        realpath(PROJECT_ROOT . '/views'),
        dirname((new \ReflectionClass('\Symfony\Bridge\Twig\AppVariable'))->getFileName()) . '/Resources/views/Form',
    ]));

    $formEngine = new TwigRendererEngine(['Forms/forms.html.twig'], $twig);
    $twig->addRuntimeLoader(new FactoryRuntimeLoader([
        FormRenderer::class => function () use ($formEngine): FormRenderer {
            return new FormRenderer($formEngine);
        },
    ]));

    $twig->addExtension(new FormExtension());

    return $twig;
});

// Make the form factory available as a service
$container->add(FormFactoryInterface::class, static fn () => Forms::createFormFactory());

require __DIR__ . '/services.php';
