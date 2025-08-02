<?php

declare(strict_types=1);

namespace App\Form\EndUser;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\CategoryDataType;
use App\Core\Entity\Category;
use App\Core\Entity\TicketRequest;
use App\Core\Repository\CategoryRepository;
use App\Core\Services\CategoryService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\TopicContact;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddTicketRequestForm extends AbstractType
{
    private readonly CategoryService $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('fullname', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                "aria-label" => "input example",
                "autocomplete" => "on",
                "placeholder" => "Họ và tên",
                "data-parsley-required-message" => "Vui lòng cung cấp Họ tên Quý Khách",
            ]
        ]);
        $builder->add('phone', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                "aria-label" => "input example",
                "autocomplete" => "on",
                "placeholder" => "Điện thoại",
                "data-parsley-type" => "integer",
                "data-parsley-type-message" => "Số điện thoại phải là số gồm 10 chữ số",
                "data-parsley-required-message" => "Vui lòng cung cấp số điện thoại của Quý Khách",
                "data-parsley-minlength-message" => "Vui lòng cung cấp chính xác số điện thoại",
                "data-parsley-minlength" => "10",
                "minlength" => "10",
            ]
        ]);
        $builder->add('email', EmailType::class, [
            'attr' => [
                'class' => 'form-control',
                "aria-label" => "input example",
                "autocomplete" => "on",
                "placeholder" => "Địa chỉ email",
                "data-parsley-type" => "email",
                "data-parsley-type-message" => "Địa chỉ email chưa đúng",
                "data-parsley-required-message" => "Vui lòng cung cấp địa chỉ email của Quý Khách",
            ]
        ]);
        $builder->add('description', TextareaType::class, [
            'attr' => [
                'class' => 'form-control',
                "aria-label" => "input example",
                "autocomplete" => "on",
                "placeholder" => "Nội dung",
//                "data-parsley-type" => "email",
//                "data-parsley-type-message" => "Địa chỉ email chưa đúng",
                "data-parsley-required-message" => "Vui lòng cung cấp nội dung",
            ]
        ]);

        $builder->add('topicName', EntityType::class, [
            'class' => Category::class,
            'attr' => [
                'class' => 'form-select',
                'data-parsley-required-message' => 'Vui lòng chọn chủ đề'
            ],
            'query_builder' => function (CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('category')
                        ->where('category.type = :type and category.isArchived = :active')
                        ->setParameter('type', CategoryDataType::TYPE_TOPIC_CONTACT)
                        ->setParameter('active', ArchivedDataType::UN_ARCHIVED);
            },
            'choice_value' => function (?Category $category) {
                if(!$category instanceof Category) return null;
                    return $category->getTitle() ?? "";
            },
            'choice_label' => function (?Category $category) {
                if(!$category instanceof Category) return null;
                return $category->getTitle() ?? "";
            },
            'placeholder' => 'Chủ đề'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TicketRequest::class,
        ]);
    }
}
