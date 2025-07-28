<?php

declare(strict_types=1);

namespace App\Form\Common;

use App\Core\DataType\MenuDataType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToggleMenuRootType extends AbstractType
{

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => [
                'MainMenu' => MenuDataType::ROOT_LEVEL,
                'SubMenu' => MenuDataType::SUB_LEVEL,
            ],
            'data' => MenuDataType::ROOT_LEVEL,
            'attr' => [
                'data-controller' => 'select2',
                'data-select2-placeholder-value' => '-- Select option --',
                'class' => 'form-select form-select-sm'
            ],
            'placeholder' => '-- Select option --',
            'multiple' => false,
            'expanded' => true,
        ]);
    }
}
