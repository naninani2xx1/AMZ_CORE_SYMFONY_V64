<?php

namespace App\Form\Admin\Setting;

use App\Core\Entity\Setting;
use App\Utils\ConvertValue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingTopic extends AbstractType
{
    public function __construct(ConvertValue $convertValue)
    {
        $this->convertValue = $convertValue;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('settingKey', TextType::class, [
                'label' => 'Tên cài đặt',
                'required' => true,
            ])

            ->add('settingValue', TextType::class, [
                'label' => 'Topic',
        ]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            $json = [
               $form->get('settingValue')->getData(),
            ];
            $data->setSettingType('topic');
            $data->setSettingValue(json_encode($json));
            $form->get('settingKey')->getData();
            $data->setSettingKey($this->convertValue->standardizationSlug($data->getSettingKey()));
        });


    }

        public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Setting::class,
        ]);
    }
}
