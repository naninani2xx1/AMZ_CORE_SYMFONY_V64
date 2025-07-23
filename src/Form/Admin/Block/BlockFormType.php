<?php

namespace App\Form\Admin\Block;

use App\Core\Entity\Block;
use App\Core\Entity\Category;
use App\Core\Entity\Post;
use App\Core\DataType\BlockType;
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
use Symfony\Component\Validator\Constraints as Assert;

class BlockFormType extends AbstractType
{
    private ImageService $imageService;
    private ConvertValue $convertValue;
    public function __construct(ImageService $imageService,  ConvertValue $convertValue)
    {
        $this->imageService = $imageService;
        $this->convertValue = $convertValue;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sortOrder', IntegerType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\PositiveOrZero(),
                ],
            ])
            ->add('title', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('subTitle', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('config', TextareaType::class, [
                'required' => false,
            ])
            ->add('content', TextareaType::class, [
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Hiện' => 0,
                    'Ẩn' => 1,
                ],
                'constraints' => [
                    new Assert\NotNull(),
                ],
            ])
            ->add('slug', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 255]),
                ],
            ]);

        foreach (['imageIcon', 'image', 'imageMobile', 'background', 'mobileBackground'] as $field) {
            $this->addImageField($builder, $field);
        }

        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Block tĩnh' => BlockType::BLOCK_KIND_DYNAMIC,
                    'Block động' => BlockType::BLOCK_KIND_STATIC,
                ],
                'required' => true,
                'constraints' => [
                    new Assert\NotNull(),
                ],
            ])
            ->add('textIcon', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('url', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 255]),
                    new Assert\Regex([
                        'pattern' => '/^https?:\/\/.+$/',
                        'message' => 'URL phải bắt đầu bằng http:// hoặc https://',
                    ]),
                ],
            ])
            ->add('location', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('videoUrl', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('kind', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('post', EntityType::class, [
                'class' => Post::class,
                'choice_label' => 'title',
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'required' => false,
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
            $data->setSlug($this->convertValue->standardizationSlug($form->get('slug')->getData()));
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
