<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\SiteType;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\SiteService;


#[Route('/site', name: 'site_')]
final class SiteController extends AbstractController
{
    private SiteService $siteService;

    public function __construct(SiteService $siteService)
    {
        $this->siteService = $siteService;
    }

    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(): Response
    {
        $sites = $this->siteService->findAllSites();
        return $this->render('site/list.html.twig',
        [
            'sites' => $sites
        ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function add(
        Request                $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $site = new Site();
        $siteForm = $this->createForm(SiteType::class, $site);
        $siteForm->handleRequest($request);

        if ($siteForm->isSubmitted() && $siteForm->isValid()) {
            $entityManager->persist($site);
            $entityManager->flush();

            $this->addFlash('success', 'Site créé avec succès');

            return $this->redirectToRoute('site_add');
        }

        return $this->render('site/add_or_edit.html.twig', [
            'controller_name' => 'SiteController',
            'site' => $site,
            'form' => $siteForm->createView(),
    ]);
    }

    #[Route('/store', name: 'store', methods: ['POST'])]
    public function store(Site $site): Response
    {
        $this->siteService->store($site);
        return $this->redirectToRoute('site_list');
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET'])]
    public function edit(int $id): Response
    {
        return $this->render('site/add_or_edit.html.twig', [
            'id' => $id,
        ]);
    }

    #[Route('/update/{id}', name: 'update', methods: ['PUT'])]
    public function update(int $id, Site $site): Response
    {
        $this->siteService->update($id, $site);
        return $this->redirectToRoute('site_list');
    }

//    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
//    public function delete(int $id): Response
//    {
//        $this->siteService()->delete($id);
//        return $this->redirectToRoute('site_list');
//    }

}
