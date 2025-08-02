<?php

namespace App\Form\Admin\Category;

use App\Core\Entity\Category;
use App\Form\Common\RecipeCategoryChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddRecipeCategoryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title', TextType::class);
        $builder->add('parent', RecipeCategoryChoiceType::class, [
            'data-select2-dropdown-parent-value' => 'form',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'attr' => [
                'data-controller' => 'Admin--category-add'
            ]
        ]);
    }
}