<?php

declare(strict_types=1);

namespace App\Form\Admin\Setting;

use App\Core\DataType\SettingDataType;
use App\Core\Entity\Setting;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditSettingForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
        /** @var Setting $setting */
        $setting = $options['data'];
        if($setting->getSettingType() == SettingDataType::SETTING_TYPE_KEY_VALUE){
            $this->buildSettingValueForm($builder);
        }elseif ($setting->getSettingType() == SettingDataType::SETTING_TYPE_JSON){
            $settingItems = new ArrayCollection();
            $json = json_decode($setting->getSettingValue(), true);
            foreach ($json as $key => $jsonItem){
                $settingItems->add(['key' => $key, 'value' => $jsonItem]);
            }
            $this->buildSettingJsonForm($builder, $settingItems);
        }else{
            $this->buildSettingImageForm($builder);
        }
    }

    public static function settingSubmit(Setting &$setting, FormInterface $form): void
    {

    }

    private function buildSettingImageForm(FormBuilderInterface $builder): void
    {
        $builder->add('settingValue', HiddenType::class, [
            'label' => 'Image Value'
        ]);
    }

    private function buildSettingJsonForm(FormBuilderInterface $builder, ArrayCollection $settingItems): void
    {
        $builder->add('settingItems', CollectionType::class, [
            'entry_type' => SettingItemType::class,
            'data' => $settingItems,
            'mapped' => false,
            'allow_add' => true,
            'allow_delete' => true,
        ]);
    }

    private function buildSettingValueForm(FormBuilderInterface $builder): void
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
