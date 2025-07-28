<?php

declare(strict_types=1);

namespace App\Form\Admin\User;

use App\Core\Entity\User;
use App\Core\Validator\User\ValidUsername;
use App\Form\Common\RolesChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddUserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var User $user */
        $user = $options['data'];
        $builder->add('username', TextType::class, [
            'constraints' => [
                $user->getId() ? new NotBlank() :  new ValidUsername()
            ]
        ]);
        $builder->add('password', TextType::class, [
            'mapped' => false,
            'required' => !$user->getId(),
        ]);
        $builder->add('roles', RolesChoiceType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'data-controller' => 'Admin--user-add'
            ]
        ]);
    }
}
