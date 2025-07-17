<?php

namespace App\Form\Admin\Menu;

use App\Core\Entity\Menu;
use App\Core\Entity\User;
use App\Services\ImageService;
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
            ->add('title',TextType::class,[
                'label'=>'Title',
            ])
            ->add('position',TextType::class,[
                'label'=>'Position',
            ])
            ->add('icon',FileType::class,[
                'label'=>'Icon',
                'required'=>false,
                'data_class'=>null,
            ])
            ->add('url',TextType::class,[
                'label'=>'Url',
            ])
            ->add('sortOrder',IntegerType::class,[
                'label'=>'Sort Order',
            ])
            ->add('language',ChoiceType::class,[
                'choices'=>[
                    'en'=>'English',
                    'vi'=>'Vietnamese',
                ],
                'label'=>'Language',
            ])
            ->add('parent', EntityType::class, [
                'class' => Menu::class,
                'choice_label' => 'title',
                'choice_value'=>'id',
                'label'=>'Parent',
                'placeholder' => '--- Không có menu cha ---',
                'required'=>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->where('m.parent IS NULL')
                        ->where('m.parent != m.parent');
                }
            ])

        ;
        $builder->addEventListener(FormEvents::SUBMIT,function(FormEvent $event){
            /* @Var Menu $menu */
            $data = $event->getData();
            $form = $event->getForm();
            /* @Var UploadedFile $filename */
            $file=$form->get('icon')->getData();
            if($file instanceof UploadedFile){
                $imgPath=$this->imageService->uploadImage($file,'uploads/menu');
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
