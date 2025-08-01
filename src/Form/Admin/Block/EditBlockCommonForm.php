<?php

declare(strict_types=1);

namespace App\Form\Admin\Block;

use App\Core\DataType\BlockDataType;
use App\Core\Entity\Block;
use App\Form\Common\Select2Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditBlockCommonForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title', TextType::class);
        $builder->add('subTitle', TextType::class, ['required' => false]);
        $builder->add('description', TextareaType::class, ['required' => false]);
        $builder->add('background', HiddenType::class, [ 'required' => false]);
        $builder->add('url', TextType::class, [ 'required' => false,]);
        $builder->add('location', Select2Type::class, [
            'required' => false,
            'choices' => [
                BlockDataType::LOCATION_LEFT => BlockDataType::LOCATION_LEFT,
                BlockDataType::LOCATION_RIGHT => BlockDataType::LOCATION_RIGHT,
            ],
            'data' => null,
            'data-select2-dropdown-parent-value' => 'form'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Block::class
        ]);
    }
}
