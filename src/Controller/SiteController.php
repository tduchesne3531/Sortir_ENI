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
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/site', name: 'site_')]
final class SiteController extends AbstractController
{
    private SiteService $siteService;

    public function __construct(SiteService $siteService)
    {
        $this->siteService = $siteService;
    }

    #[Route('/', name: 'list', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function list(): Response
    {
        $sites = $this->siteService->findAllSites();
        return $this->render('site/list.html.twig',
            [
                'sites' => $sites
            ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function addOrEdit(
        Request                $request,
        EntityManagerInterface $entityManager,
        int $id = null
    ): Response
    {
        $site = $id ? $this->siteService->getById($id) : new Site();
        $siteForm = $this->createForm(SiteType::class, $site);
        $siteForm->handleRequest($request);

        if ($siteForm->isSubmitted() && $siteForm->isValid()) {
            $entityManager->persist($site);
            $entityManager->flush();

            $this->addFlash('success', 'Site créé/modifié avec succès');

            return $this->redirectToRoute('site_list');
        }

        return $this->render('site/add_or_edit.html.twig', [
            'controller_name' => 'SiteController',
            'site' => $site,
            'form' => $siteForm->createView(),
        ]);
    }

    #[Route('/store', name: 'store', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function store(Site $site): Response
    {
        $this->siteService->store($site);
        return $this->redirectToRoute('site_list');
    }

    #[Route('/search', name: 'search', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function search(Request $request): Response
    {
        $word = $request->query->get('search', '');
        $sites = $this->siteService->getAllByWord($word);

        return $this->render('site/list.html.twig', [
            'sites' => $sites,
            'search' => $word,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(int $id): Response
    {
        $this->siteService->deleteById($id);
        return $this->redirectToRoute('site_list');
    }

}
