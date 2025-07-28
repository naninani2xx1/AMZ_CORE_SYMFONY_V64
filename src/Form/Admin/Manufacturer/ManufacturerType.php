<?php

namespace App\Form\Admin\Manufacturer;

use App\Entity\Manufacturer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;

class ManufacturerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Tên không được để trống.']),
                    new Length(['max' => 255, 'maxMessage' => 'Tên tối đa {{ limit }} ký tự.']),
                ],
            ])
            ->add('phone', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Số điện thoại không được để trống.']),
                    new Regex([
                        'pattern' => '/^[0-9+\-\s()]+$/',
                        'message' => 'Số điện thoại không hợp lệ.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Email không được để trống.']),
                    new Email(['message' => 'Email không hợp lệ.']),
                ],
            ])
            ->add('address', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Địa chỉ không được để trống.']),
                    new Length(['max' => 255, 'maxMessage' => 'Địa chỉ tối đa {{ limit }} ký tự.']),
                ],
            ])
            ->add('urlWeb', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'URL website không được để trống.']),
                    new Url(['message' => 'Đường dẫn không hợp lệ.']),
                ],
            ])
            ->add('products',TextType::class,[
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('sortOrder', IntegerType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Thứ tự sắp xếp không được để trống.']),
                ],
            ]);
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options) {
            $form=$event->getForm();
            $data=$form->getData();
            $productString = $form->get('products')->getData() ?? '';
            $convertProduct = array_filter(array_map('trim', explode(',', $productString)));
            $data->setProducts($convertProduct);
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Manufacturer::class,
            'attr' => ['novalidate' => 'novalidate','data-controller'=>'Admin--manufacturer'],
        ]);
    }
}
