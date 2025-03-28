<?php

namespace App\Controller;


use App\dto\ActivityFilter;
use App\Entity\Activity;
use App\Entity\Participant;
use App\Entity\Place;
use App\Entity\Site;
use App\Entity\State;
use App\Form\ActivitiesFilterType;
use App\Form\ActivityType;
use App\Form\PlaceType;
use App\Repository\StateRepository;
use App\Service\ActivityService;
use App\Service\ParticipantService;
use App\Service\SiteService;
use App\Service\StateService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/activity', name: 'activity_')]
final class ActivityController extends AbstractController
{

    private ActivityService $activityService;
    private SiteService $siteService;
    private StateService $stateService;
    private StateRepository $stateRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param ActivityService $activityService
     * @param SiteService $siteService
     * @param StateService $stateService
     * @param StateRepository $stateRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ActivityService $activityService, SiteService $siteService, StateService $stateService, StateRepository $stateRepository, EntityManagerInterface $entityManager)
    {
        $this->activityService = $activityService;
        $this->siteService = $siteService;
        $this->stateService = $stateService;
        $this->stateRepository = $stateRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'list', methods: ['GET', 'POST'])]
    public function list(
        Request $request,
    ): Response
    {
        $filter = new ActivityFilter();
        $activityFilterForm = $this->createForm(ActivitiesFilterType::class, $filter);
        $activityFilterForm->handleRequest($request);

        $user = $this->getUser();
            if ($user != null && !$user instanceof Participant)
                throw new \LogicException('L’utilisateur connecté n’est pas un participant.');

        if (!($activityFilterForm->isSubmitted() && $activityFilterForm->isValid())) {
            $filter->setPast(false);
            $filter->setArchived(false);
        }
        $filter->setUser($user != null ? $user : null);

        $activities = $this->stateService->verifyAndChange(
            $this->activityService->getAllByFilter($filter)
        );


        return $this->render('activity/list.html.twig', [
            'form' => $activityFilterForm->createView(),
            'activities' => $activities,
            'user' => $user,
        ]);
    }

    #[Route('/publish/{id}', name: 'publish', methods: ['GET', 'POST'])]
    public function publish(int $id): Response
    {
        $activity = $this->activityService->getById($id);
        if (!$activity)
            throw $this->createNotFoundException("Activity with ID $id not found.");

        $activity->setState($this->stateRepository->find(2));
        $this->entityManager->flush();

        return $this->redirectToRoute('activity_list');
    }

    #[Route('/{id}', name: 'detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id): Response
    {
        $user = $this->getUser();
        if ($user != null && !$user instanceof Participant)
            throw $this->createAccessDeniedException('Vous devez être un participant pour accéder aux détails de cette sortie.');

        $activity = $this->activityService->getById($id);
        if (!$activity)
            throw $this->createNotFoundException('Cette sortie n\'existe pas.');

        $isUserRegistered = $activity->getParticipants()->contains($user != null ? $user : new Participant());
        $isManager = $activity->getManager() === $user;

        return $this->render('activity/detail.html.twig', [
            'activity' => $activity,
            'isUserRegistered' => $isUserRegistered,
            'isManager' => $isManager,
            'user' => $user,
        ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function add(
        Request                $request,
        EntityManagerInterface $entityManager): Response
    {
        $activity = new Activity();
        $activityForm = $this->createForm(ActivityType::class, $activity);
        $activityForm->handleRequest($request);

        if ($activityForm->isSubmitted() && $activityForm->isValid()) {
            $user = $this->getUser();
            $action = $request->request->get('state');
            $activity->setState($this->stateRepository->find($action));
            $activity->setCreatedBy($this->getUser());
            $activity->setManager($user instanceof Participant ? $user : null);
            $entityManager->persist($activity);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie créée avec succès !');

            return $this->redirectToRoute('activity_list');
        }

        $placeForm = $this->createForm(PlaceType::class, new Place());


        return $this->render('activity/addOrEdit.html.twig', [
            'form' => $activityForm->createView(),
            'activity' => $activity,
            'placeForm' => $placeForm->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $activity = $this->activityService->getById($id);
        $activityForm = $this->createForm(ActivityType::class, $activity);
        $activityForm->handleRequest($request);

        if ($activityForm->isSubmitted() && $activityForm->isValid()) {
            $activity->setUpdatedBy($this->getUser());
            $activity->setUpdatedAt(new \DateTime('now'));
            $entityManager->flush();

            $this->addFlash('success', 'Sortie modifiée avec succès !');

            return $this->redirectToRoute('activity_list');

        }
        $placeForm = $this->createForm(PlaceType::class, new Place());

        return $this->render('activity/addOrEdit.html.twig', [
            'form' => $activityForm->createView(),
            'activity' => $activity,
            'placeForm' => $placeForm->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $activity = $this->activityService->getById($id);
        $entityManager->remove($activity);
        $entityManager->flush();

        $this->addFlash('success', 'Sortie supprimée avec succès !');

        return $this->redirectToRoute('activity_list');
    }

    #[Route('/{id}/register', name: 'register', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function register(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Participant)
            throw $this->createAccessDeniedException('Vous devez être un participant pour vous inscrire.');

        $activity = $this->activityService->getById($id);

        if ($activity->getParticipants()->contains($user)) {
            $this->addFlash('warning', 'Vous êtes déjà inscrit à cette sortie.');
        } elseif ($activity->getParticipants()->count() >= $activity->getMaxRegistration()) {
            $this->addFlash('danger', 'Cette sortie est complète.');
        } else {
            $activity->addParticipant($user);
            $entityManager->flush();
            $this->addFlash('success', 'Inscription réussie !');
        }

        return $this->redirectToRoute('activity_detail', ['id' => $id]);
    }

    #[Route('/{id}/unregister', name: 'unregister', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function unregister(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Participant)
            throw $this->createAccessDeniedException('Vous devez être un participant pour vous désinscrire.');

        $activity = $this->activityService->getById($id);

        if ($activity->getParticipants()->contains($user)) {
            $activity->removeParticipant($user);
            $entityManager->flush();
            $this->addFlash('success', 'Vous êtes désinscrit de cette sortie.');
        } else {
            $this->addFlash('warning', 'Vous n\'êtes pas inscrit à cette sortie.');
        }

        return $this->redirectToRoute('activity_detail', ['id' => $id]);
    }

    #[Route('/{id}/cancel', name: 'cancel', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function cancel(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Participant)
            throw $this->createAccessDeniedException('Vous devez être connecté en tant que participant pour annuler une sortie.');

        $activity = $this->activityService->getById($id);
        if (!$activity)
            throw $this->createNotFoundException('Cette sortie n\'existe pas.');
        if ($activity->getManager() !== $user && !$this->isGranted('ROLE_ADMIN'))
            throw $this->createAccessDeniedException('Seul le créateur ou un administrateur peut annuler cette sortie.');

        if ($activity->getState()->getId() === 6) {
            $activity->setState($this->stateRepository->find(2));
            $entityManager->flush();
            return $this->redirectToRoute('activity_detail', ['id' => $id]);
        }

        $cancelReason = $request->request->get('cancelReason');
        if (empty($cancelReason)) {
            $this->addFlash('danger', 'Un motif d’annulation est requis.');
            return $this->redirectToRoute('activity_detail', ['id' => $id]);
        }

        // Récupération de l'état "Annulée"
        $canceledState = $entityManager->getRepository(State::class)->findOneBy(['name' => 'Annulée']);
        if (!$canceledState)
            throw new \LogicException('L\'état "Annulée" est introuvable.');

        // Mise à jour de l’état et ajout du motif
        $activity->setState($canceledState);
        $activity->setCancelReason($cancelReason);
        $entityManager->flush();

        $this->addFlash('success', 'La sortie a été annulée avec succès.');

        return $this->redirectToRoute('activity_detail', ['id' => $id]);
    }

}
