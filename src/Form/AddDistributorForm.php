<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Distributor;
use App\Form\Common\TagType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddDistributorForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('companyName', TextType::class);
        $builder->add('companyAddress', TextType::class);
        $builder->add('phone', TextType::class);
        $builder->add('email', TextType::class);
        $builder->add('webLink', TextType::class, ['required' => false]);
        $builder->add('products', TagType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Distributor::class,
            'attr' => [
                'data-controller' => 'Admin--distributor-add',
            ]
        ]);
    }
}
