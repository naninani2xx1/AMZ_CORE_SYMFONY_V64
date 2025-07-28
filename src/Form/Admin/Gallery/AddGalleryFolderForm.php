<?php

declare(strict_types=1);

namespace App\Form\Admin\Gallery;

use App\Core\DataType\GalleryDataType;
use App\Core\Entity\Gallery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddGalleryFolderForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => false,
            'attr' => [
                'class' => 'form-control fs-7 h-35px',
                'placeholder' => 'Enter Name',
            ],
            'constraints' => [
                new NotBlank(),
            ]
        ]);
        $builder->add('type', HiddenType::class);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            /** @var Gallery $gallery **/
           $gallery =  $event->getForm()->getData();
           $gallery->setType(GalleryDataType::TYPE_FOLDER);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gallery::class,
            'attr'=>['novalidate'=>'novalidate','data-controller'=>'Admin--gallery'],
        ]);
    }
}
