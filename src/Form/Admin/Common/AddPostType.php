<?php

declare(strict_types=1);

namespace App\Form\Admin\Common;

use App\Core\Entity\Post;
use App\Core\Services\PictureService;
use App\Form\Common\PublishedChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddPostType extends AbstractType
{
    private PictureService $pictureService;
    public function __construct(PictureService $pictureService)
    {
        $this->pictureService = $pictureService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title');
        $builder->add('subTitle', TextType::class, ['required' => false]);
        $builder->add('description', TextareaType::class, [
            'required' => false,
        ]);
        $builder->add('thumbnail', HiddenType::class, ['required' => false]);
        $builder->add('published', PublishedChoiceType::class, [
            'data-select2-dropdown-parent-value' => '#amz_post_add',
            'data-select2-hidden-search-value' => 'true'
        ]);
        $builder->add('arrTags', TextType::class, [
            'label' => 'Tags',
            'attr' => [
                'placeholder' => 'Enter tags',
                'class' => 'form-control form-control-sm form-control-solid mb-2',
                'data-controller' => 'tagify',
                'data-tagify-default-value' => 'abc,def,ghi',
            ]
        ]);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $post = $event->getData();
            $form = $event->getForm();
            if ($post instanceof Post) {
                $form->add('isHot', HiddenType::class, ['required' => false]);
                $form->add('isNew', HiddenType::class, ['required' => false]);
                $form->add('content', TextareaType::class, [
                    'required' => false,
                ]);
            }
        });

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            /** @var Post $data */
            $post = $event->getData();
            $thumbnail = $post->getThumbnail();
            if(is_int($thumbnail)){
                $picture = $this->pictureService->findById($thumbnail);
                $post->setThumbnail($picture->getImage());
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
