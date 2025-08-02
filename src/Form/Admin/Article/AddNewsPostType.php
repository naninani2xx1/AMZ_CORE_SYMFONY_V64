<?php

declare(strict_types=1);

namespace App\Form\Admin\Article;

use App\Core\DataType\PostStatusType;
use App\Core\DataType\PostTypeDataType;
use App\Core\Entity\Post;
use App\Form\Common\CkeditorType;
use App\Form\Common\PublishedChoiceType;
use App\Form\Common\SocialSharingType;
use App\Form\Common\TagType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddNewsPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Post $post */
        $post = $options['data'];
        $builder->add('title', TextType::class);
        $builder->add('description', TextareaType::class, ['required' => false]);
        $builder->add('content', CkeditorType::class, ['required' => false]);
        $builder->add('arrTags', TagType::class, ['required' => false]);
        $builder->add('thumbnail', HiddenType::class, ['required' => false]);
        $builder->add('published', PublishedChoiceType::class, [
            'data-select2-dropdown-parent-value' => '#article-form',
            'data' => $post instanceof Post ? $post->getPublished() : PostStatusType::PUBLISH_TYPE_PUBLISHED,
        ]);
        $builder->add('isHot', HiddenType::class, ['required' => false]);
        $builder->add('isNew', HiddenType::class, ['required' => false]);

        $builder->add('socialSharing', SocialSharingType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
