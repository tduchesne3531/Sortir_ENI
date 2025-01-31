<?php

namespace App\Controller;

use App\Form\CsvUploadType;
use App\Service\CsvImporter;
use App\Utils\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use function Symfony\Component\Clock\now;

#[Route('/admin', name: 'admin_')]
final class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    #[Route('/import', name: 'import')]
    #[IsGranted('ROLE_ADMIN')]
    public function import(Request $request, FileUploader $fileUploader, CsvImporter $csvImporter, #[CurrentUser] $user): Response
    {
        $form = $this->createForm(CsvUploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('csvFile')->getData();

            if ($file) {
                $filePath = $fileUploader->upload($file, 'import-csv', 'imports');

                try {
                    $errors = $csvImporter->import($filePath, $user);

                    if (!empty($errors)) {
                        foreach ($errors as $error) {
                            dd($error);
                            $form->addError(new \Symfony\Component\Form\FormError($error));
                        }
                    } else {
                        $this->addFlash('success', 'Importation réussie !');
                        return $this->redirectToRoute('admin_import');
                    }
                } catch (\InvalidArgumentException $e) {
                    $form->addError(new \Symfony\Component\Form\FormError($e->getMessage()));
                }

                return $this->redirectToRoute('admin_import');
            }
        }

        return $this->render('admin/import.html.twig', [
            'form' => $form,
        ]);
    }
}
