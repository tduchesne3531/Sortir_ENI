<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType;
use App\mapper\ActivityMapper;
use App\Repository\ParticipantRepository;
use App\Repository\PlaceRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Service\ActivityService;
use App\Service\PlaceService;
use App\Service\SiteService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/activity', name: 'activity_')]
final class ActivityController extends AbstractController
{

    private SiteRepository $siteRepository;
    private ActivityService $activityService;
    private SiteService $siteService;
    private ParticipantRepository $participantRepository;
    private ActivityMapper $activityMapper;
    private PlaceService $placeService;

    /**
     * @param SiteRepository $siteRepository
     * @param ActivityService $activityService
     * @param SiteService $siteService
     * @param ParticipantRepository $participantRepository
     * @param ActivityMapper $activityMapper
     * @param PlaceService $placeService
     */
    public function __construct(SiteRepository $siteRepository, ActivityService $activityService, SiteService $siteService, ParticipantRepository $participantRepository, ActivityMapper $activityMapper, PlaceService $placeService)
    {
        $this->siteRepository = $siteRepository;
        $this->activityService = $activityService;
        $this->siteService = $siteService;
        $this->participantRepository = $participantRepository;
        $this->activityMapper = $activityMapper;
        $this->placeService = $placeService;
    }


    #[Route('/all', name: 'all', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getAll(ActivityMapper $activityMapper): Response
    {
        $user = $this->getUser();
        $participant = $user
            ? $this->participantRepository->find(['email' => $user->getUserIdentifier()])
            : $this->participantRepository->find(['id' => 1]);
        $activities = $this->activityService->getAll();
        $sites = $this->siteService->findAllSites();
        $activitiesDto = array_map(fn($activity) => $this->activityMapper->toDto($activity, $participant), $activities);

        return $this->render('activity/activities.html.twig', [
            'controller_name' => 'ActivityController',
            'activities' => $activitiesDto,
            'participant' => $participant,
            'sites' => $sites,
        ]);
    }

    #[Route('/site/{siteId}', name: 'by_site', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
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

    #[Route('/activity/{activityId}', name: 'by_id', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
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

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    #[Route('/edit/{id}', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function save(
        Request                $request,
        EntityManagerInterface $entityManager,
        SortieRepository       $activityRepository,
        PlaceRepository        $placeRepository,
        int                    $id = null
    ): Response
    {
        $activity = $id ? $activityRepository->find($id) : new Activity();
        if (!$activity)
            throw $this->createNotFoundException('activity not found');

        $places = $this->placeService->getAll();

        $activityForm = $this->createForm(ActivityType::class, $activity);
        $activityForm->handleRequest($request);

        if ($activityForm->isSubmitted() && $activityForm->isValid()) {
            $placeId = $request->request->get('places');
            $place = $this->$placeRepository->find($placeId);
            $activity->setPlace($place);

            $entityManager->persist($activity);
            $entityManager->flush();
        }

        return $this->render('activity/activity_form.html.twig', [
            'controller_name' => 'ActivityController',
            'places' => $places,
            'form' => $activityForm->createView(),
            'activity' => $activity,
        ]);
    }

}
