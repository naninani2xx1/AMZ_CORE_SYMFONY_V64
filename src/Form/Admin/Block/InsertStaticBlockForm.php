<?php

namespace App\Form\Admin\Block;

use App\Core\Entity\Block;
use App\Core\Entity\Page;
use App\Form\Common\BlockType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InsertStaticBlockForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Page $page */
       $page = $options['data'];
        $builder->add('post', InsertPostStaticBlockType::class, [
            'data' =>  $page->getPost(),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
            'attr' => [
                'data-controller' => 'Admin--block-add'
            ]
        ]);
    }
}