<?php

namespace App\Controller;


use App\Entity\Activity;
use App\Entity\Participant;
use App\Entity\State;
use App\Form\ActivityType;
use App\Repository\StateRepository;
use App\Service\ActivityService;
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

    public function __construct(ActivityService $activityService, SiteService $siteService, StateService $stateService, StateRepository $stateRepository)
    {
        $this->activityService = $activityService;
        $this->siteService = $siteService;
        $this->stateService = $stateService;
        $this->stateRepository = $stateRepository;
    }

    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(): Response
    {
        $activities = $this->stateService->verfiAndChange(
            $this->activityService->getAllIsArchive(true)
        );
        $sites = $this->siteService->findAllSites();

        return $this->render('activity/list.html.twig', [
            'activities' => $activities,
            'sites' => $sites
        ]);
    }

    #[Route('/{id}', name: 'detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id): Response
    {
        $user = $this->getUser();

        if (!$user instanceof Participant) {
            throw $this->createAccessDeniedException('Vous devez être un participant pour accéder aux détails de cette sortie.');
        }

        $activity = $this->activityService->findById($id);

        if (!$activity) {
            throw $this->createNotFoundException('Cette sortie n\'existe pas.');
        }

        $isUserRegistered = $activity->getParticipants()->contains($user);
        $isManager = $activity->getManager() === $user;

        return $this->render('activity/detail.html.twig', [
            'activity' => $activity,
            'isUserRegistered' => $isUserRegistered,
            'isManager' => $isManager,
        ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function add(
        Request $request,
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

        return $this->render('activity/addOrEdit.html.twig', [
            'form' => $activityForm->createView(),
            'activity' => $activity
            ]);
    }

    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $activity = $this->activityService->findByID($id);
        $activityForm = $this->createForm(ActivityType::class, $activity);
        $activityForm->handleRequest($request);

        if ($activityForm->isSubmitted() && $activityForm->isValid()) {
            $activity->setUpdatedBy($this->getUser());
            $activity->setUpdatedAt(new \DateTime('now'));
            $entityManager->flush();

            $this->addFlash('success', 'Sortie modifiée avec succès !');

            return $this->redirectToRoute('activity_list');

        }
        return $this->render('activity/addOrEdit.html.twig', [
            'form' => $activityForm->createView(),
            'activity' => $activity
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $activity = $this->activityService->findById($id);
        $entityManager->remove($activity);
        $entityManager->flush();

        $this->addFlash('success', 'Sortie supprimée avec succès !');

        return $this->redirectToRoute('activity_list');
    }

    #[Route('/{id}/register', name: 'register', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function register(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof Participant) {
            throw $this->createAccessDeniedException('Vous devez être un participant pour vous inscrire.');
        }

        $activity = $this->activityService->findByID($id);

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

        if (!$user instanceof Participant) {
            throw $this->createAccessDeniedException('Vous devez être un participant pour vous désinscrire.');
        }

        $activity = $this->activityService->findByID($id);

        if ($activity->getParticipants()->contains($user)) {
            $activity->removeParticipant($user);
            $entityManager->flush();
            $this->addFlash('success', 'Vous êtes désinscrit de cette sortie.');
        } else {
            $this->addFlash('warning', 'Vous n\'êtes pas inscrit à cette sortie.');
        }

        return $this->redirectToRoute('activity_detail', ['id' => $id]);
    }

    #[Route('/{id}/cancel', name: 'cancel', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function cancel(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof Participant) {
            throw $this->createAccessDeniedException('Vous devez être connecté en tant que participant pour annuler une sortie.');
        }

        $activity = $this->activityService->findByID($id);

        if (!$activity) {
            throw $this->createNotFoundException('Cette sortie n\'existe pas.');
        }

        if ($activity->getManager() !== $user && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Seul le créateur ou un administrateur peut annuler cette sortie.');
        }

        $cancelReason = $request->request->get('cancelReason');
        if (empty($cancelReason)) {
            $this->addFlash('danger', 'Un motif d’annulation est requis.');
            return $this->redirectToRoute('activity_detail', ['id' => $id]);
        }

        // Récupération de l'état "Annulée"
        $canceledState = $entityManager->getRepository(State::class)->findOneBy(['name' => 'Annulée']);
        if (!$canceledState) {
            throw new \LogicException('L\'état "Annulée" est introuvable.');
        }

        // Mise à jour de l’état et ajout du motif
        $activity->setState($canceledState);
        $activity->setCancelReason($cancelReason);
        $entityManager->flush();

        $this->addFlash('success', 'La sortie a été annulée avec succès.');

        return $this->redirectToRoute('activity_detail', ['id' => $id]);
    }

}
