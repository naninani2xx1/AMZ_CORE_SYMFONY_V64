<?php

declare(strict_types=1);

namespace App\Form\Admin\Qa;

use App\Core\Entity\Qa;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddQaForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('question', TextareaType::class);
        $builder->add('answer', TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Qa::class,
            'attr' => [
                'data-controller' => 'Admin--qa-add'
            ]
        ]);
    }
}
