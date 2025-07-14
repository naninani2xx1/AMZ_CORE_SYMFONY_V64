<?php

namespace App\Form\Admin\Block;

use App\Core\Entity\Block;
use App\Core\Entity\Category;
use App\Core\Entity\Post;
use App\Core\DataType\BlockType;
use App\Services\ImageService;
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

class BlockFormType extends AbstractType
{
    private ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sortOrder', IntegerType::class)
            ->add('title', TextType::class)
            ->add('subTitle', TextType::class)
            ->add('description', TextareaType::class)
            ->add('config', TextareaType::class)
            ->add('content', TextareaType::class)
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Hiện' => 0,
                    'Ẩn' => 1,
                ],
            ])
            ->add('slug', TextType::class);
        foreach (['imageIcon', 'image', 'imageMobile', 'background', 'mobileBackground'] as $field) {
            $this->addImageField($builder, $field);
        }

        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Block động' => BlockType::BLOCK_KIND_DYNAMIC,
                    'Block tĩnh' => BlockType::BLOCK_KIND_STATIC,
                ],
                'required' => true,
            ])
            ->add('textIcon', TextType::class)
            ->add('url', TextType::class)
            ->add('location', TextType::class)
            ->add('videoUrl', TextType::class)
            ->add('kind', TextType::class)
            ->add('post', EntityType::class, [
                'class' => Post::class,
                'choice_label' => 'title',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
            ]);
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var Block $data */
            $data = $event->getData();
            $form = $event->getForm();

            foreach (['imageIcon', 'image', 'imageMobile', 'background', 'mobileBackground'] as $field) {
                /** @var UploadedFile|null $file */
                $file = $form->get($field)->getData();
                if ($file instanceof UploadedFile) {
                    $imgPath = $this->imageService->uploadImage($file, 'uploads/blocks');
                    $setter = 'set' . ucfirst($field);
                    if (method_exists($data, $setter)) {
                        $data->$setter($imgPath);
                    }
                }
            }
        });
    }

    private function addImageField(FormBuilderInterface $builder, string $fieldName): void
    {
        $builder->add($fieldName, FileType::class, [
            'required' => false,
            'mapped' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Block::class,
        ]);
    }
}
