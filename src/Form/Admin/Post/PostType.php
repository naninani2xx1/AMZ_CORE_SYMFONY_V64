<?php

namespace App\Form\Admin\Post;

use App\Core\Entity\Post;
use App\DataType\PostTypeChoice;
use App\Services\Admin\ImageService;
use App\Utils\ConvertValue;
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
use Symfony\Component\Validator\Constraints as Assert;

class PostType extends AbstractType
{
    private ImageService $imageService;
    private ConvertValue $convertValue;
    public function __construct(ImageService $imageService,ConvertValue $convertValue )
    {
        $this->imageService = $imageService;
        $this->convertValue = $convertValue;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Tiêu đề không được để trống.']),
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('subTitle', TextType::class, [
                'constraints' => [
                    new Assert\Length(['max' => 255]),
                ],
                'required' => false,
            ])
            ->add('description', TextType::class, [

                'required' => false,
            ])
            ->add('url', TextType::class, [
                'constraints' => [
                    new Assert\Length(['max' => 255]),
                    new Assert\Url(['message' => 'Đường dẫn URL không hợp lệ.']),
                ],
                'required' => false,
            ])

            ->add('thumbnail', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('slug', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Slug không được để trống.']),
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('sortOrder', IntegerType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\PositiveOrZero(),
                ],
            ])
            ->add('content', TextareaType::class, [
                'required' => false,
            ])
            ->add('isHot', ChoiceType::class, [
                'choices' => [
                    'Nổi bật' => 1,
                    'Bình thường' => 0
                ],
                'constraints' => [
                    new Assert\NotNull(),
                ],
            ])
            ->add('isNew', ChoiceType::class, [
                'choices' => [
                    'Mới nhất' => 0,
                    'Cũ' => 1
                ],
                'constraints' => [
                    new Assert\NotNull(),
                ],
            ])
            ->add('postType', ChoiceType::class, [
                'choices' => array_flip(PostTypeChoice::TYPE_LABELS),
                'multiple' => false,
                'expanded' => false,
                'constraints' => [
                    new Assert\NotNull(),
                ],
            ])
            ->add('published', ChoiceType::class, [
                'choices' => [
                    'Xuất bản' => true,
                    'Bản nháp' => false,
                ],
                'constraints' => [
                    new Assert\NotNull(),
                ],
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Post|null $post */
            $post = $event->getData();
            $form = $event->getForm();

            $tags = $post?->getTags() ?? [];
            $convertTag = implode(',', $tags);

            $form->add('tag', TextType::class, [
                'data' => $convertTag,
                'mapped' => false,
                'required' => false,
                'empty_data' => '',
            ]);
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options) {
            /** @var Post $post */
            $post = $event->getData();
            $form = $event->getForm();

            /** @var UploadedFile|null $post_img */
            $post_img = $form->get('thumbnail')->getData();
            if ($post_img instanceof UploadedFile) {
                $imgPath = $this->imageService->uploadImage($post_img, 'uploads/posts');
                $post->setThumbnail($imgPath);
                if (!empty($options['gallery'])) {
                    $this->imageService->saveImageToGallery($imgPath, $post->getTitle(), $options['gallery']);
                }
            }
            $post->setSlug($this->convertValue->standardizationSlug($form->get('slug')->getData()));
            $tagString = $form->get('tag')->getData() ?? '';
            $convertTag = array_filter(array_map('trim', explode(',', $tagString)));
            $post->setTags($convertTag);
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
