<?php

namespace App\Form\Admin\Post;


use App\Core\Entity\Gallery;
use App\Core\Entity\Post;
use App\Core\Repository\GalleryRepository;
use App\DataType\PostTypeChoice;
use App\Services\ImageService;
use App\Utils\ConvertValue;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    private $imageService;
    public function __construct(ImageService $imageService){
        $this->imageService = $imageService;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('subTitle', TextType::class)
            ->add('url', TextType::class)
            ->add('thumbnail', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('slug', TextType::class)
            ->add('sortOrder', IntegerType::class)
            ->add('description', TextareaType::class)
            ->add('content', TextareaType::class)
            ->add('isHot', ChoiceType::class, [
                'choices' => [
                    'Nổi bật '=>1,
                    'Bình thường'=>0
                ]
            ])
            ->add('isNew', ChoiceType::class, [
                'choices' => [
                    'Mới nhất'=>0,
                    'Cũ'=>1
                ]
            ])
            ->add('postType', ChoiceType::class, [
                'choices' => array_flip(PostTypeChoice::TYPE_LABELS),
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('published', ChoiceType::class, [
                'choices' => [
                    'Xuất bản'=>true,
                    'Bản nháp'=>false,
                ]
            ])
//            ->add('config', )
//            ->add('article', EntityType::class, [
//                'class' => Article::class,
//                'choice_label' => 'id',
//            ])
//            ->add('socialSharing', EntityType::class, [
//                'class' => SocialSharing::class,
//                'choice_label' => 'id',
//            ])
//            ->add('page', EntityType::class, [
//                'class' => Page::class,
//                'choice_label' => 'id',
//            ])
          ;
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                /* @Var Post $post */
            $post = $event->getData();
            $form = $event->getForm();
                /* @Var UploadedFile $file */
            $post_img = $form->get('thumbnail')->getData();
            if ($post_img instanceof UploadedFile) {
                $imgPath = $this->imageService->uploadImage($post_img, 'uploads/posts');
                $post->setThumbnail($imgPath);
            }
            $post->setSubTitle(ConvertValue::standardizationDash($post->getSubTitle()));
            $convertTag=explode(',',$form->get('tag')->getData());
            $post->setTag($convertTag);
        });
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $post = $event->getData();
            $form = $event->getForm();

            $convertTag=implode(',',$post->getTag()??[]);
            $form->add('tag',TextType::class,[
                'data' => $convertTag,
                'mapped' => false,
            ]);
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
