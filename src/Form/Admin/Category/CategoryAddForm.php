<?php

declare(strict_types=1);

namespace App\Form\Admin\Category;

use App\Core\Entity\Category;
use App\Utils\ConvertValue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Services\ImageService;

class CategoryAddForm extends AbstractType
{
    private ConvertValue $convertValue;
    private ImageService $imageService;

    public function __construct(ConvertValue $convertValue, ImageService $imageService)
    {
        $this->convertValue = $convertValue;
        $this->imageService = $imageService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Category Name',
                'row_attr' => ['class' => 'mb-5'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Vui lòng nhập tên danh mục.']),
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('slug', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Slug không được để trống.']),
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('icon', FileType::class, [
                'required' => false,
                'mapped'=>false
            ])
            ->add('thumbnail', FileType::class, [
                'required' => false,
                'mapped'=>false
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('customPath', TextareaType::class, [
                'required' => false,
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $event->getForm()->add('parent', CategoryChoiceType::class, [
                'required' => false,
            ]);
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var Category $category */
            $category = $event->getData();
            $form = $event->getForm();

            $category->setSlug($this->convertValue->standardizationSlug($form->get('slug')->getData()));

            /** @var UploadedFile|null $fileIcon */
            $fileIcon = $form->get('icon')->getData();
            if ($fileIcon instanceof UploadedFile) {
                $imgPath = $this->imageService->uploadImage($fileIcon, 'uploads/category/icon');
                $category->setIcon($imgPath);
            }

            /** @var UploadedFile|null $fileThumbnail */
            $fileThumbnail = $form->get('thumbnail')->getData();
            if ($fileThumbnail instanceof UploadedFile) {
                $imgPath = $this->imageService->uploadImage($fileThumbnail, 'uploads/category/thumbnail');
                $category->setThumbnail($imgPath);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'attr' => [
                'id' => 'category_add_form',
                'data-action' => 'submit->modal#submitDefault:prevent',
                'data-modal-selector-param' => '#product_add_form',
                'data-modal-event-name-param' => 'category:add:success',
            ],
        ]);
    }
}
