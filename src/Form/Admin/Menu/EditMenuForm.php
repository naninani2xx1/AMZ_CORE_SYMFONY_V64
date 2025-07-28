<?php

declare(strict_types=1);

namespace App\Form\Admin\Menu;

use App\Core\DataType\MenuDataType;
use App\Core\Entity\Menu;
use App\Form\Common\PositionMenuChoiceType;
use App\Form\Common\ToggleMenuRootType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditMenuForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Menu $menu */
        $menu = $options['data'];
        $builder->add('name', TextType::class);
        $builder->add('position', PositionMenuChoiceType::class);

        if($menu->getIsRoot() == MenuDataType::SUB_LEVEL)
            $builder->add('url', TextType::class);
    }

    public function onPreSubmit(FormEvent $event): void
    {
        $form = $event->getForm()->getParent();
        $data = array_filter($event->getData(), function ($value) {return $value !== null;});
        $val = current($data);
        if($val == MenuDataType::SUB_LEVEL)
            $form->add('url', TextType::class);
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
