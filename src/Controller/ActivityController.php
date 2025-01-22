<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Service\ActivityService;
use App\Service\SiteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ActivityController extends AbstractController
{

    private SiteRepository $siteRepository;
    private ActivityService $activityService;
    private SiteService $siteService;
    private ParticipantRepository $participantRepository;

    /**
     * @param SiteRepository $siteRepository
     * @param ActivityService $activityService
     * @param SiteService $siteService
     * @param ParticipantRepository $participantRepository
     */
    public function __construct(SiteRepository $siteRepository, ActivityService $activityService, SiteService $siteService, ParticipantRepository $participantRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->activityService = $activityService;
        $this->siteService = $siteService;
        $this->participantRepository = $participantRepository;
    }


    #[Route('/activity', name: 'app_all_activityies', methods: ['GET'])]
    public function getAll(): Response
    {
        $user = $this->getUser();
        $participant = $user ? $this->participantRepository->find(['email' => $user->getUserIdentifier()]) : null;
        $activities = $this->activityService->getAll();
        $sites = $this->siteService->findAllSites();

        return $this->render('activity/activities.html.twig', [
            'controller_name' => 'ActivityController',
            'activities' => $activities,
            'participant' => $participant,
            'sites' => $sites,
        ]);
    }

    #[Route('/activity/site/{siteId}', name: 'app_activities_by_site', methods: ['GET'])]
    public function getAllBySite(int $siteId): Response
    {
        $site = $this->siteRepository->find($siteId);
        $activities = $this->activityService->getAllBySite($site);
        $user = $this->getUser();
        $sites = $this->siteRepository->findAll();

        return $this->render('activity/activities.html.twig', [
            'controller_name' => 'ActivityController',
            'activities' => $activities,
            'user' => $user,
            'sites' => $sites,
        ]);
    }

    #[Route('/activity/{activityId}', name: 'app_activity', methods: ['GET'])]
    public function get(int $activityId): Response
    {
        $activity = $this->activityService->get($activityId);
        $user = $this->getUser();

        return $this->render('activity/activity.html.twig', [
            'controller_name' => 'ActivityController',
            'activity' => $activity,
            'user' => $user
        ]);
    }

}
