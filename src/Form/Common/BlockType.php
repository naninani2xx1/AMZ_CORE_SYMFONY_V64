<?php

declare(strict_types=1);

namespace App\Form\Common;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockType extends AbstractType
{
    private $parameterBag;
    public function __construct(
        ParameterBagInterface $parameterBag
    )
    {
        $this->parameterBag = $parameterBag;
    }
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    private function getBlocks(): array
    {
        $results = array();
        $blocks = $this->parameterBag->get('blocks_type');

        foreach ($blocks as $type => $block) {
            $results[$block['name']] = $type;
        }

        return $results;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => $this->getBlocks(),

            'attr' => [
                'data-controller' => 'select2',
                'data-select2-placeholder-value' => '-- Select option --',
                'data-select2-dropdown-parent-value' => '#app-modal',
                'data-select2-hidden-search-value' => 'false',
                'class' => 'form-select form-select-sm'
            ],
            'placeholder' => '-- Select option --',
            'multiple' => false,

        ]);
//        $resolver->setDefined(['data-select2-dropdown-parent-value']);
//        $resolver->setDefined(['data-select2-hidden-search-value']);
//
//        $resolver->setNormalizer('attr', function (Options $options, $value) {
//            if (isset($options['data-select2-dropdown-parent-value'])) {
//                $value['data-select2-dropdown-parent-value'] = $options['data-select2-dropdown-parent-value'];
//            }
//            if (isset($options['data-select2-hidden-search-value'])) {
//                $value['data-select2-hidden-search-value'] = $options['data-select2-hidden-search-value'];
//            }
//            return $value;
//        });
    }
}
