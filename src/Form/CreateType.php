<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

final class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('description', TextareaType::class, [
            'label' => false,
            'required' => true,
            'attr' => [
                'rows' => 10,
                'placeholder' => 'Generate a creative description for a unique fantasy world with an interesting concept around cities build on the backs of massive beasts.',
            ],
        ]);
    }
}