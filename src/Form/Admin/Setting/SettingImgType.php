<?php

namespace App\Form\Admin\Setting;

use App\Core\Entity\Gallery;
use App\Core\Entity\Setting;
use App\Services\Admin\ImageService;
use App\Utils\ConvertValue;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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

    public function __construct(ImageService $imageService,ConvertValue $convertValue)
    {
        $this->imageService = $imageService;
        $this->convertValue = $convertValue;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('settingKey', TextType::class)
            ->add('settingValue',FileType::class,[
                'required' => true,
                'mapped' => false,

            ])
            ->add('gallery', EntityType::class, [
                'class' => Gallery::class,
                'choice_label' => 'name',
                'label' => 'Chọn thư mục Gallery',
                'required' => true,
                'placeholder' => '-- Chọn gallery --'
                ,'mapped'=>false
            ])
        ;
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var Setting $setting */
            $setting = $event->getData();
            $form = $event->getForm();

            /** @var UploadedFile|null $file */
            $file = $form->get('settingValue')->getData();
            /** @var Gallery|null $gallery */
            $gallery = $form->get('gallery')->getData();

            if ($file instanceof UploadedFile && $gallery instanceof Gallery) {
                $imagePath = $this->imageService->uploadImage($file, 'uploads/settings');

                if ($imagePath) {
                    $setting->setSettingValue($imagePath);
                    $this->imageService->saveImageToGallery($imagePath, $setting->getSettingKey(), $gallery);
                }
            }

            $setting->setSettingType('image');
            $setting->setSettingKey($this->convertValue->standardizationSlug($setting->getSettingKey()));
        });


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Setting::class,
        ]);
    }
}
