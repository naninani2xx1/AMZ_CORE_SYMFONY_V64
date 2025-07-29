<?php

declare(strict_types=1);

namespace App\Form\Admin\Menu;

use App\Core\DataType\MenuDataType;
use App\Core\Entity\Menu;
use App\Form\Common\PositionMenuChoiceType;
use App\Form\Common\ToggleMenuRootType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddMenuChildForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class);
        $builder->add('url', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
            'attr' => [
                'data-controller' => 'Admin--menu-add',
            ]
        ]);
    }
}
