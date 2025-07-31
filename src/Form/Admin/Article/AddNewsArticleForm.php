<?php

declare(strict_types=1);

namespace App\Form\Admin\Article;

use App\Core\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddNewsArticleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ?Article $article */
        $article = $options['data'];
        $builder->add('post', AddNewsPostType::class, [
            'data' => $article->getPost()
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => Article::class,
            'attr' => [
                'id' => 'article-form',
                'data-controller' => 'Admin--article-add',
                'data-Admin--article-add-target' => 'form',
                'data-Admin--article-add-ckeditor-outlet' => '[data-controller="ckeditor"]',
            ]
        ));
    }
}
