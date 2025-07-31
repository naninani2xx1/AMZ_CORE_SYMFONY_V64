<?php

declare(strict_types=1);

namespace App\Form\Common;

use App\Core\DataType\PostStatusType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublishedChoiceType extends AbstractType
{
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => [
                'Published' => PostStatusType::PUBLISH_TYPE_PUBLISHED,
                'Draft' =>  PostStatusType::PUBLISH_TYPE_DRAFT,
            ],
            'data' => PostStatusType::PUBLISH_TYPE_PUBLISHED,
            'attr' => [
                'data-controller' => 'select2',
                'data-select2-placeholder-value' => '-- Select option --',
                'class' => 'form-select form-select-sm',
            ],
            'placeholder' => '-- Select option --',
        ]);

        $resolver->setDefined(['data-select2-dropdown-parent-value']);
        $resolver->setDefined(['data-select2-hidden-search-value']);

        $resolver->setNormalizer('attr', function (Options $options, $value) {
            if (isset($options['data-select2-dropdown-parent-value'])) {
                $value['data-select2-dropdown-parent-value'] = $options['data-select2-dropdown-parent-value'];
            }
            if (isset($options['data-select2-hidden-search-value'])) {
                $value['data-select2-hidden-search-value'] = $options['data-select2-hidden-search-value'];
            }
            return $value;
        });
    }
}
