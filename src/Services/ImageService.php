<?php
namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService
{
    public function __construct(private readonly ParameterBagInterface $parameterBag)
    {
    }

    public function uploadImage(UploadedFile $file, string $relativePath, ?string $customName = null): string
    {
        $projectDir = $this->parameterBag->get('kernel.project_dir');
        $uploadPath = $projectDir . '/public/' . trim($relativePath, '/');

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $fileName = $customName ?? uniqid() . '.' . $file->guessExtension();
        $file->move($uploadPath, $fileName);

        return '/'. trim($relativePath, '/') . '/' . $fileName;
    }
}
