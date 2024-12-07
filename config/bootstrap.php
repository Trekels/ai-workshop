<?php

declare(strict_types=1);

use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormRenderer;
use League\Container as LeagueContainer;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\FormFactoryInterface;

$container = new LeagueContainer\Container();
$container->delegate(new LeagueContainer\ReflectionContainer());

$container->add(Twig\Environment::class, function () {
    $viewsDirectory = realpath(__DIR__ . '/../views');
    $appVariableReflection = new \ReflectionClass('\Symfony\Bridge\Twig\AppVariable');
    $vendorTwigBridgeDirectory = dirname($appVariableReflection->getFileName());

    $twig = new Twig\Environment(new Twig\Loader\FilesystemLoader([
        $viewsDirectory,
        $vendorTwigBridgeDirectory . '/Resources/views/Form',
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

$container->add(FormFactoryInterface::class, static fn () => Forms::createFormFactory());

