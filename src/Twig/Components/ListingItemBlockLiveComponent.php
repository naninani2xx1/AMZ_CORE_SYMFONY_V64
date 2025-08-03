<?php

namespace App\Twig\Components;

use App\Core\Entity\Block;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;


#[AsLiveComponent(template: 'components/ListingItemLiveComponent.html.twig')]
final class ListingItemBlockLiveComponent
{
    use DefaultActionTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    #[LiveProp(writable: true)]
    public ?Block $block = null;

    #[LiveProp(writable: true)]
    public ?string $template = '';

    #[LiveAction]
    public function addItem(): void
    {
        $content = json_decode($this->block->getContent(), true);
        $item = [
            'uuid' => Uuid::v4()->toRfc4122(),
            'title' => 'What is Lorem Ipsum?',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            'customUrl' => '#demo',
            'subTitle' => 'Lorem Ipsum',
            'image' => '',
            'background' => ''
        ];

        $content['listingItem'][$item['uuid']] = $item;
        $this->block->setContent(json_encode($content));

        $this->entityManager->flush();
    }
}
