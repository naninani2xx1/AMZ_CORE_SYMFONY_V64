<?php

declare(strict_types=1);

namespace App\Form\Admin\Setting;

use App\Core\DataType\SettingDataType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingOptionType extends AbstractType
{
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => [
                "Setting Image" => SettingDataType::SETTING_TYPE_IMAGE,
                "Setting Key&Value" => SettingDataType::SETTING_TYPE_KEY_VALUE,
                "Setting Dynamic" => SettingDataType::SETTING_TYPE_JSON,
            ],
            'data' => SettingDataType::SETTING_TYPE_KEY_VALUE,
            'placeholder' => null,
            'expanded' => true,
            'multiple' => false,
        ]);
    }
}
