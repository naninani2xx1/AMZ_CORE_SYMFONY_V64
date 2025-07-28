<?php

declare(strict_types=1);

namespace App\Form\Common;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PositionMenuChoiceType extends AbstractType
{
    private $parameterBag;
    public function __construct(
        ParameterBagInterface $parameterBag
    )
    {
        $this->parameterBag = $parameterBag;
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    private function getPositions(): array
    {
        $menuConfig = $this->parameterBag->get('menu.config');
        return array_flip($menuConfig['positions']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => $this->getPositions(),
            'attr' => [
                'data-controller' => 'select2',
                'data-select2-placeholder-value' => '-- Select option --',
                'data-select2-dropdown-parent-value' => '#app-modal',
                'data-select2-hidden-search-value' => 'false',
                'class' => 'form-select form-select-sm'
            ],
            'placeholder' => '-- Select option --',
            'multiple' => false,
        ]);
    }
}
