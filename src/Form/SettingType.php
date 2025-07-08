<?php

namespace App\Form;

use App\Core\Entity\Setting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('settingKey', TextType::class, [
                'label' => 'Tên cài đặt',
                'required' => true,
            ])

            ->add('width', TextType::class, [
                'label' => 'Chiều rộng',
                'mapped' => false,
                'required' => false,
            ])
            ->add('height', TextType::class, [
                'label' => 'Chiều cao',
                'mapped' => false,
                'required' => false,
            ]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            $json = [
                'width' => $form->get('width')->getData(),
                'height' => $form->get('height')->getData(),
            ];
            $data->settingType('size');
            $data->setSettingValue(json_encode($json));
        });


    }

        public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Setting::class,
        ]);
    }
}
