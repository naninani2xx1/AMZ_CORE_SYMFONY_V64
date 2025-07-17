<?php

declare(strict_types=1);

namespace App\Form\Admin\Category;


use App\Core\Entity\Category;
use App\Core\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Admin\Category\CategoryChoiceType;
class CategoryAddForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title', TextType::class, [
            'label' => 'Category Name',
            'row_attr' => ['class' => 'mb-5']
        ])
            ->add('slug', TextType::class)
            ->add('icon', FileType::class, [
                'required' => false,
                'data_class' => null,
            ])
            ->add('thumbnail', FileType::class, [
                'required' => false,
                'data_class' => null,
            ])
            ->add('description', TextareaType::class)
            ->add('customPath', TextareaType::class)
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $event->getForm()->add('parent', CategoryChoiceType::class, [
                'required' => false,
            ]);
        });
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var Category $category */
            $category = $event->getData();
            $form = $event->getForm();
            /** @var UploadedFile $fileIcon */
            $fileIcon=$form->get('icon')->getData();
            if($fileIcon instanceof UploadedFile){
                $uploadDirectory = __DIR__.'/../public/uploads/category/icon';
                $newFilename=uniqid().'.'.$fileIcon->guessExtension();
                $fileIcon->move($uploadDirectory,$newFilename);
                $category->setIcon($newFilename);
            }

            /** @var UploadedFile $fileThumbnail */
            $fileThumbnail=$form->get('thumbnail')->getData();
            if($fileThumbnail instanceof UploadedFile){
                $uploadDirectory = __DIR__.'/../public/uploads/category/thumbnail';
                $newFilename=uniqid().'.'.$fileThumbnail->guessExtension();
                $fileThumbnail->move($uploadDirectory,$newFilename);
                $category->setThumbnail($newFilename);

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
                'data-modal-selector-param' => '#product_add_form', // dispatch event to dom element - #product_add_form
                'data-modal-event-name-param' => 'category:add:success' // event dispatched
            ]
        ]);
    }
}