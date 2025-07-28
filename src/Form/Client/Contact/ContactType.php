<?php

namespace App\Form\Client\Contact;

use App\Core\Repository\SettingRepository;
use App\DataType\ContactTypeChoice;
use App\Entity\TopicContact;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    private $settingRepository;
    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class)
            ->add('email',EmailType::class)
            ->add('phone',TextType::class)
            ->add('topic',EntityType::class,[
                'class'=>TopicContact::class,
                'choice_label'=>'topic',
                'placeholder' => 'Chọn chủ đề',
                'required'=>true,
                'multiple'=>false,
            ])
            ->add('status', HiddenType::class, [
                'data'=>1
            ])
            ->add('content',TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
