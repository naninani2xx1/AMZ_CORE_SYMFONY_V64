<?php

declare(strict_types=1);

namespace App\Form\EndUser;

use App\Core\Entity\TicketRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddTicketRequestForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TicketRequest::class,
        ]);
    }
}
