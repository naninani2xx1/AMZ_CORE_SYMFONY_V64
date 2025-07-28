<?php

declare(strict_types=1);

namespace App\Form\Admin\Setting;

use App\Core\DataType\SettingDataType;
use App\Core\Entity\Setting;
use App\Core\Validator\Setting\ValidSettingKey;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddSettingForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('settingType', SettingOptionType::class);
        $builder->add('description', TextareaType::class, [
            'attr' => [
                'class' => 'form-control fs-7',
                'rows' => 3,
                'placeholder' => 'Description for the key',
            ],
            'label' => 'Description',
            'constraints' => [
                new NotBlank(message: 'Description cannot be blank.'),
            ]
        ]);
        $builder->add('settingKey', TextType::class, [
            'help' => 'Enter text containing the character "-"',
            'help_attr' => [
                'class' => 'fs-8 fw-semibold text-gray-700 my-2'
            ],
            'constraints' => [
                new ValidSettingKey()
            ]
        ]);

        $builder->get('settingType')->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'settingTypeInit']);
        $builder->get('settingType')->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'settingTypeChanged']);
    }

    public static function settingSubmit(Setting &$setting, FormInterface $form): void
    {
        if($setting->getSettingType() == SettingDataType::SETTING_TYPE_JSON){
            $items = $form->get('settingItems')->getData();
            if($items instanceof ArrayCollection)
                $items = $items->toArray();
            $outputArray = array_combine(
                array_column($items, 'key'),
                array_column($items, 'value')
            ) ?: [];
            $setting->setSettingValue(json_encode($outputArray));
        }
    }

    public function settingTypeInit(FormEvent $event): void
    {
        $form = $event->getForm()->getParent();
        $settingTypeVal = $event->getData();
        // null is event pre-set-data, reserve pre-submit
        if($settingTypeVal == SettingDataType::SETTING_TYPE_KEY_VALUE){
            $this->buildSettingValueForm($form);
        }elseif ($settingTypeVal == SettingDataType::SETTING_TYPE_IMAGE) {
            $this->buildSettingImageForm($form);
        }else{
            $this->buildSettingJsonForm($form);
        }
    }

    public function settingTypeChanged(FormEvent $event): void
    {
        $form = $event->getForm()->getParent();
        $settingTypeVal = $event->getData();

        $nonNullValues = array_values(array_filter($settingTypeVal, function($value) {
            return $value !== null;
        }));

        $settingTypeVal = current($nonNullValues);
        // null is event pre-set-data, reserve pre-submit
        if($settingTypeVal == SettingDataType::SETTING_TYPE_KEY_VALUE){
            $this->buildSettingValueForm($form);
        }elseif ($settingTypeVal == SettingDataType::SETTING_TYPE_IMAGE) {
            $this->buildSettingImageForm($form);
        }else{
            $this->buildSettingJsonForm($form);
        }
        $event->stopPropagation();
    }

    private function buildSettingImageForm(FormInterface $builder): void
    {
        $builder->add('settingValue', HiddenType::class, [
            'label' => 'Image Value'
        ]);
    }

    private function buildSettingJsonForm(FormInterface $builder): void
    {
        $builder->add('settingItems', CollectionType::class, [
            'entry_type' => SettingItemType::class,
            'mapped' => false,
            'allow_add' => true,
        ]);
    }

    private function buildSettingValueForm(FormInterface $builder): void
    {
        $builder->add('settingValue', TextareaType::class, [
            'label' => 'Your Value',
            'attr' => [
                'rows' => 5,
                'class' => 'form-control fs-7',
                'placeholder' => 'Enter value',
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Setting::class,
            'attr' => [
                'data-controller' => 'Admin--setting-add'
            ]
        ]);
    }
}
