<?php

namespace App\Form\Admin\Menu;

use App\Core\Entity\Menu;
use App\Services\Admin\ImageService;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class MenuType extends AbstractType
{
    private ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Vui lòng nhập tiêu đề.']),
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('position', TextType::class, [
                'label' => 'Position',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Vui lòng nhập vị trí.']),
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('icon', FileType::class, [
                'label' => 'Icon',
                'required' => false,
                'data_class' => null,
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Chỉ chấp nhận hình ảnh JPEG, PNG hoặc WebP.',
                    ]),
                ],
            ])
            ->add('url', TextType::class, [
                'label' => 'URL',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Vui lòng nhập đường dẫn URL.']),
                    new Assert\Url(['message' => 'URL không hợp lệ.']),
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('sortOrder', IntegerType::class, [
                'label' => 'Thứ tự',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Vui lòng nhập thứ tự sắp xếp.']),
                    new Assert\PositiveOrZero(['message' => 'Giá trị phải là số không âm.']),
                ],
            ])
            ->add('language', ChoiceType::class, [
                'choices' => [
                    'English' => 'en',
                    'Vietnamese' => 'vi',
                ],
                'label' => 'Ngôn ngữ',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Vui lòng chọn ngôn ngữ.']),
                ],
            ])
            ->add('parent', EntityType::class, [
                'class' => Menu::class,
                'choice_label' => 'title',
                'choice_value' => 'id',
                'label' => 'Menu cha',
                'placeholder' => '--- Không có menu cha ---',
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->where('m.parent IS NULL')
                        ->orderBy('m.title', 'ASC');
                },
            ]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var Menu $data */
            $data = $event->getData();
            $form = $event->getForm();

            /** @var UploadedFile|null $file */
            $file = $form->get('icon')->getData();
            if ($file instanceof UploadedFile) {
                $imgPath = $this->imageService->uploadImage($file, 'uploads/menu');
                $data->setIcon($imgPath);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
