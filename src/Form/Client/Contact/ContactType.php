<?php

namespace App\Form\Client\Contact;

use App\Core\Repository\SettingRepository;
use App\DataType\ContactTypeChoice;
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
        $topics = $this->settingRepository->findTopics();

        $choices = [];

        foreach ($topics as $topic) {
          $decode=json_decode($topic->getSettingValue());
          $items = explode(',', $decode[0]);
          foreach ($items as $item) {
              $trimItems = trim($item);
              $choices[$trimItems] = $trimItems;
          }
        }

        $builder
            ->add('name',TextType::class)
            ->add('email',EmailType::class)
            ->add('phone',TextType::class)
            ->add('topic', ChoiceType::class, [
                'choices' => $choices,
                'multiple' => false,
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
