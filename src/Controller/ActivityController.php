<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ActivityController extends AbstractController
{

    private SortieRepository $sortieRepository;
    private SiteRepository $siteRepository;

    public function __construct(SortieRepository $sortieRepository, SiteRepository $siteRepository)
    {
        $this->sortieRepository = $sortieRepository;
        $this->siteRepository = $siteRepository;
    }

    #[Route('/activity', name: 'app_all_activityies', methods: ['GET'])]
    public function getAll(): Response
    {
        $activities = $this->sortieRepository->findAll();
        $user = $this->getUser();
        $sites = $this->siteRepository->findAll();

        return $this->render('activity/activity.html.twig', [
            'controller_name' => 'ActivityController',
            'activities' => $activities,
            'user' => $user,
            'sites' => $sites,
        ]);
    }

    #[Route('/activity/site/{siteId}', name: 'app_activities_by_site', methods: ['GET'])]
    public function getAllBySite(int $siteId): Response
    {
        $site = $this->siteRepository->find($siteId);
        $activities = $this->sortieRepository->findBy(['site' => $site], ['date' => 'DESC']);
        $user = $this->getUser();
        $sites = $this->siteRepository->findAll();

        return $this->render('activity/activity.html.twig', [
            'controller_name' => 'ActivityController',
            'activities' => $activities,
            'user' => $user,
            'sites' => $sites,
        ]);
    }
}
