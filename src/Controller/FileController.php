<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends AbstractController
{
    #[Route('/uploads/{filePath}', name: 'file_download', requirements: ['filePath' => '.+'])]
    public function download(string $filePath): Response
    {
        $file = $this->getParameter('directory') . '/' . $filePath;

        if (!file_exists($file)) {
            throw $this->createNotFoundException('File not found.');
        }

        return new BinaryFileResponse($file);
    }
}