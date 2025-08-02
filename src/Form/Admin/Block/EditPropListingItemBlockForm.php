<?php

namespace App\Form\Admin\Block;

use App\Core\Entity\Block;
use App\Form\Common\CkeditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditPropListingItemBlockForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if($options['property'] == 'description')
            $type = CkeditorType::class;
        else
            $type = TextType::class;
        $builder->add($options['property'], $type, [
            'data' => $options['value'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'property' => 'title',
            'value' => null,
            'attr' => [
                'data-controller' => 'Admin--block-prop-edit',
            ]
        ]);
    }
}