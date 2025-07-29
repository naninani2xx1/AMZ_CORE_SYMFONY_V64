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

class AddMenuForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class);
        $builder->add('position', PositionMenuChoiceType::class);
        $builder->add('isRoot', ToggleMenuRootType::class);

//        $builder->get('isRoot')->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
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
