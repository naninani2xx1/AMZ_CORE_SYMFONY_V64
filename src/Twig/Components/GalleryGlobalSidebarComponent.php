<?php

namespace App\Twig\Components;

use App\Core\DataType\GalleryDataType;
use App\Core\Entity\Gallery;
use App\Form\Admin\Gallery\AddGalleryFolderForm;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;


#[AsLiveComponent(template: 'components/GalleryGlobalSidebarComponent.html.twig')]
final class GalleryGlobalSidebarComponent extends BaseTableLiveComponent
{
    use ComponentWithFormTrait, ComponentToolsTrait;

    #[LiveProp(writable: ['name'])]
    public ?Gallery $gallery = null;
    #[LiveProp(writable: true)]
    public ?bool $hasAddNew = false;

    protected function getQueryBuilder(): QueryBuilder
    {
        $qb = $this->entityManager->getRepository(Gallery::class)->createQueryBuilder('gallery');
        $qb->where(
            $qb->expr()->eq('gallery.isArchived', $qb->expr()->literal(false)),
            $qb->expr()->eq('gallery.type', $qb->expr()->literal(GalleryDataType::TYPE_FOLDER)),
        );

        return $qb;
    }

    protected function getSearchColumns(): array
    {
        return array('gallery.name');
    }

    #[PostMount]
    public function onPostMount(): void
    {
        $this->gallery = $this->entityManager->getRepository(Gallery::class)->findOneBy(['type' => GalleryDataType::TYPE_FOLDER]);
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(AddGalleryFolderForm::class, new Gallery());
    }

    #[LiveAction]
    public function saveNewGallery(): void
    {
        $this->submitForm();
        $gallery = $this->getForm()->getData();
        $this->entityManager->persist($gallery);
        $this->entityManager->flush();

        $this->resetForm();
        $this->hasAddNew = false;
    }

    #[LiveAction]
    public function addNew(): void
    {
        $this->hasAddNew = !$this->hasAddNew;
    }

    #[LiveAction]
    public function setGallery(#[LiveArg] Gallery $gallery): void
    {
        $this->gallery = $gallery;
        $this->emit('picture_listing_component.change_gallery', array(
            'id' => $this->gallery->getId(),
        ), 'PictureGlobalListingComponent');
    }
}
