<?php

namespace App\Form\Client\Contact;

use App\Core\Repository\SettingRepository;
use App\Entity\Contact;
use App\Entity\TopicContact;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    private  $settingRepository;
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
            ->add('topic', EntityType::class, [
                'class' => TopicContact::class,
                'choice_label' => 'name',
                'placeholder' => 'Chọn chủ đề',
                'required' => true,
                'multiple' => false,
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->where('t.isArchived = :isArchived')
                        ->setParameter('isArchived', 0);
                },
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
            'data_class' => Contact::class,
        ]);
    }
}
