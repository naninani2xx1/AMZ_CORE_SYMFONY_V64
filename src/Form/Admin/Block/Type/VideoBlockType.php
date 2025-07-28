<?php
namespace App\Form\Admin\Block\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class VideoBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('videoUrl', TextType::class, [
                'label' => 'Đường dẫn video',
                'required' => false,
            ]);
    }
}
