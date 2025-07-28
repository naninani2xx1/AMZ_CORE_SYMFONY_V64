<?php

namespace App\Twig\Components;

use App\Core\Entity\Gallery;
use App\Core\Entity\Picture;
use App\Core\Services\FileUploadService;
use App\Core\Services\PictureService;
use App\Core\Trait\EnvironmentTrait;
use App\Core\Trait\FileTrait;
use App\Core\Trait\FileValidationTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent(template: 'components/PictureListingComponent.html.twig')]
final class PictureGlobalListingComponent extends BaseTableLiveComponent
{
    use EnvironmentTrait, FileValidationTrait, FileTrait;
    private FileUploadService $fileUploadService;
    private KernelInterface $kernel;
    private PictureService $pictureService;
    public function __construct(EntityManagerInterface $entityManager, PaginatorInterface $paginator,
              KernelInterface $kernel, PictureService $pictureService,   FileUploadService $fileUploadService)
    {
        parent::__construct($entityManager, $paginator);
        $this->fileUploadService = $fileUploadService;
        $this->kernel = $kernel;
        $this->pictureService = $pictureService;
    }

    #[LiveProp(writable: true)]
    public ?Gallery $gallery = null;

    public array $flashMessages = [];
    protected function getQueryBuilder(): ?QueryBuilder
    {
        if(!$this->gallery instanceof Gallery)
            return null;
        $qb = $this->entityManager->getRepository(Picture::class)->createQueryBuilder('picture');
        $qb->join('picture.gallery', 'gallery');
        $qb->where(
            $qb->expr()->eq('picture.isArchived', $qb->expr()->literal(false)),
            $qb->expr()->eq('gallery.id', $qb->expr()->literal($this->gallery->getId())),
        );
        return $qb;
    }

    protected function getSearchColumns(): array
    {
        return array(); // no - search
    }

    #[LiveListener('picture_listing_component.change_gallery')]
    public function changeGallery(#[LiveArg] $id): void
    {
        $gallery = $this->entityManager->getRepository(Gallery::class)->find($id);
        $this->gallery = $gallery;
    }


    #[LiveAction]
    public function uploadPicture(Request $request): void
    {
        $multiple = $request->files->all('multiple');
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $allowedMaxFileSize = 1024 * 1024 * 3; // 3MB
        // Handle files
        /** @var UploadedFile $file */
        foreach ($multiple as $file) {
            $extension = $this->getExtension($file->getClientOriginalName());
            if($this->isValidExtension($extension, $allowedExtensions) && $allowedMaxFileSize >= $file->getSize()) {
                $url = $this->fileUploadService->upload($file);
                $this->pictureService->addGlobalByGallery($this->gallery, $url, $file->getClientOriginalName());
            }else{
              // add errors file invalid
                $this->addFlash('error', [
                    'Invalid file extension or size with: '. $file->getClientOriginalName(). '| '. $this->formatFileSize($file->getSize()),
                ]);
            }
        }
        if(empty($multiple))
            $this->addFlash('error', [
                'Please upload at least one picture.',
            ]);
    }
}
