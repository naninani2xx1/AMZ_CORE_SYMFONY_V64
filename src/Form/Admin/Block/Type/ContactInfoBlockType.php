<?php
// src/Form/Admin/Block/Type/ContactInfoBlockType.php

namespace App\Form\Admin\Block\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ContactInfoBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class, ['required' => false])
            ->add('phone', TextType::class, ['required' => false])
            ->add('email', TextType::class, ['required' => false])
            ->add('organization_1', TextType::class, ['required' => false])
            ->add('organization_2', TextType::class, ['required' => false])
            ->add('organization_3', TextType::class, ['required' => false]);
    }
}
    