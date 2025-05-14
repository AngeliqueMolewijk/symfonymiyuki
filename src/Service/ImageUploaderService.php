<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploaderService
{
    private string $targetDirectory;
    private string $defaultImage;

    public function __construct(string $targetDirectory, string $defaultImage = 'noimage.jpeg')
    {
        $this->targetDirectory = $targetDirectory;
        $this->defaultImage = $defaultImage;
    }

    public function upload(UploadedFile $file): string
    {
        if (!$file) {
            return $this->defaultImage;
        }
        $newFilename =  uniqid('', true) . '.' . $file->guessExtension();

        $file->move(
            $this->getTargetDirectory(),
            $newFilename
        );

        return $newFilename;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
