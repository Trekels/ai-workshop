<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\CreateType;
use App\Repository\WorldRepository;
use App\Service\World\WorldGenerator;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Twig\Environment;

final readonly class CreateController
{
    public function __construct(
        private Environment $twig,
        private WorldGenerator $worldGenerator,
        private WorldRepository $worldRepository,
        private FormFactoryInterface $formFactory,
    ) {}

    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(CreateType::class);
        $form->handleRequest();

        if ($form->isSubmitted()) {
            $description = $form->getData()['description'];
            $world = $this->worldGenerator->generate($description);

            $this->worldRepository->save($world);

            return new Response(302, ['Location' => '/']);
        }

        return new Response(body: $this->twig->render('World/create.html.twig', [
            'form' => $form->createView(),
        ]));
    }
}