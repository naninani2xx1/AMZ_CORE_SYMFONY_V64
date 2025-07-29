<?php

namespace App\Core\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

class FileUploadService
{
    private string $targetDirectory;

    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file): string
    {
        $allowedExtensions = ['jpeg', 'webp', 'png', 'jpg','svg'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowedExtensions)) {
            throw new FileException('Invalid file format. Only .jpeg, .webp, .png, .jpg are allowed.');
        }

        // Tạo UUID cho tên file
        $uuid = Uuid::v4()->toRfc4122();
        $fileName = $uuid . '.' . $extension;

        // Tạo cấu trúc thư mục: public/pictures/{year}/{month}
        $year = date('Y');
        $month = date('m');
        $directory = $this->targetDirectory . '/' . $year . '/' . $month;

        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        try {
            $file->move($directory, $fileName);
        } catch (FileException $e) {
            throw new FileException('Failed to upload file: ' . $e->getMessage());
        }

        return "pictures/$year/$month/$fileName";
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }


}