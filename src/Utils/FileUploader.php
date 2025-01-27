<?php

namespace App\Utils;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function __construct(
        private readonly string $directory,
        LoggerInterface $logger
    )
    {
        $logger->error($directory);
    }

    public function upload(UploadedFile $uploadedFile,  string $name, string $subDirectory = ''): string
    {
        $fileName = (!empty($name) ? $name . '-' : '') . uniqid() . '.' . $uploadedFile->guessExtension();
        $targetDir = $this->directory . '/' . trim($subDirectory, '/');

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $uploadedFile->move($targetDir, $fileName);
        return ($subDirectory ? $subDirectory . '/' : '') . $fileName;
    }

    public function remove(string $filePath): void
    {
        $fullPath = $this->directory . DIRECTORY_SEPARATOR . $filePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}