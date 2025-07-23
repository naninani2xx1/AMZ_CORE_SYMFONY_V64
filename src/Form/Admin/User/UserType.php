<?php

namespace App\Form\Admin\User;

use App\Core\Entity\User;
use App\Utils\ConvertValue;
use App\Utils\UserUtil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',TextType::class)
            ->add('password',PasswordType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Root' => 'ROLE_ROOT',
                    'Admin Post' => 'ROLE_ADMIN_POST',
                    'Admin Page' => 'ROLE_ADMIN_PAGE',
                ],
                'multiple' => true,
                'expanded' => false,
            ]);
        ;
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /* @var User $user */
            $user = $event->getData();
            $replace_username = ConvertValue::standardizationSpace($user->getUsername()) ;
            $replace_password = ConvertValue::standardizationSpace($user->getPassword()) ;
            $user->setUsername($replace_username);
            $user->setPassword($replace_password);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
