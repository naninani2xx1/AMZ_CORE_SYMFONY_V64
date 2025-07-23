<?php

namespace App\Form\Admin\Setting;

use App\Core\Entity\Setting;
use App\Services\ImageService;
use App\Utils\ConvertValue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingImgType extends AbstractType
{
    private ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('settingKey', TextType::class)
            ->add('settingValue',FileType::class,[
                'required' => true,
                'mapped' => false
            ])

        ;
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var Setting $setting */
            $setting = $event->getData();
            $form = $event->getForm();

            /** @var UploadedFile|null $file */
            $file = $form->get('settingValue')->getData();
            if ($file instanceof UploadedFile) {
                $imagePath = $this->imageService->uploadImage($file, 'uploads/settings');
                $setting->setSettingValue($imagePath);
            }
            $setting->setSettingType('image');
            $setting->setSettingKey(ConvertValue::standardizationDash($setting->getSettingKey()));
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Setting::class,
        ]);
    }
}
