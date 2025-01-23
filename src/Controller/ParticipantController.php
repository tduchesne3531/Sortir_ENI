<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\User;
use App\Form\ParticipantType;
use App\Service\ParticipantService;
use App\Service\SiteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/participant/', name: 'participant_')]
final class ParticipantController extends AbstractController
{
    private participantService $participantService;
    private siteService $siteService;

    public function __construct(ParticipantService $participantService, SiteService $siteService)
    {
        $this->participantService = $participantService;
        $this->siteService = $siteService;
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): Response
    {
//        $isAdmin = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

//        if ($isAdmin) {
            $participants = $this->participantService->getAllParticipants();

            return $this->render('participant/list.html.twig', [
                'participants' => $participants,
            ]);
//        }

//        return $this->redirectToRoute('home');
    }

    #[Route('detail/{id}', name: 'detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id, Participant $participant, #[CurrentUser] ?User $user): Response{
        $mode = 'read';

        $isAdmin = $this->isGranted('ROLE_ADMIN');

        if ($isAdmin || $user->getId() === $id) {
            return $this->render('participant/detail.html.twig', [
                'participant' => $participant,
                'mode' => $mode,
            ]);
        }
        return $this->redirectToRoute('home');
    }

    #[Route('add', name: 'add', methods: ['GET', 'POST'])]
//    #[IsGranted(
//        attribute: new Expression('user === subject or is_granted("ROLE_ADMIN")'),
//        subject: new Expression('args["participant"].getUser()')
//    )]
//    TODO: make a single route for both add and edit ?
//    #[Route('edit/{id}', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function addParticipant(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
    ): Response{
        $mode = 'add';
        $isAdmin = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($isAdmin) {
            $participant = new Participant();
            $participantForm = $this->createForm(ParticipantType::class, $participant);
            $participantForm->handleRequest($request);

            if ($participantForm->isSubmitted() && $participantForm->isValid()) {
                /** @var string $plainPassword */
                $plainPassword = $participantForm->get('plainPassword')->getData();
                $participant->setPassword($userPasswordHasher->hashPassword($participant, $plainPassword));

                $participant->setIsActive(true);

                // Define createdAt explicitly
                if (!$participant->getCreatedAt()) {
                    $participant->setCreatedAt(new \DateTimeImmutable());
                }

                $site = new Site();
                $site->setName('Rennes');
                $site->setCreatedAt(new \DateTimeImmutable());
//                $site->
                $this->siteService->store($site);
                $participant->setSite($site);

                $this->participantService->storeOrUpdateParticipant($participant, $site);
                $this->addFlash('success', 'The participant' . $participant->getFirstname() . 'was successfully created.');
                return $this->redirectToRoute('participant_detail', ['id' => $participant->getId()]);
            }
            // else return the form to add a new participant
            return $this->render('participant/add_or_edit.html.twig', [
                'mode' => $mode,
                'participant' => $participant,
                'participantForm' => $participantForm,
            ]);
        }
        return $this->render('home/index.twig', []);
    }

    #[Route('edit/{id}', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function editAccount(int $id, Request $request, #[CurrentUser] ?User $user): Response
    {
        $mode = 'edit';
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        if ($isAdmin || $user->getId() === $id) {
            $participant = $this->participantService->getParticipantById($id);
            $participantForm = $this->createForm(ParticipantType::class, $participant);
            $participantForm->handleRequest($request);

            if ($participantForm->isSubmitted() && $participantForm->isValid()) {
                $this->participantService->storeOrUpdateParticipant($participant, $site);
                $this->addFlash('success', 'The participant' . $participant->getFirstname() . 'was successfully edited.');

                return $this->redirectToRoute('detail', ['id' => $participant->getId()]);
            }

            return $this->render('participant/add_or_edit.html.twig', [
                'mode' => $mode,
                'participant' => $participant,
                'participantForm' => $participantForm,
            ]);
        }

//        return $this->render('participant/add_or_edit.html.twig', [
//            'id' => $id,
//            'participant' => $participant,
//            'mode' => $mode,
//        ]);
        return $this->redirectToRoute('home');
    }

    #[Route('delete/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function deleteParticipant(int $id): Response
    {
        $isAdmin = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($isAdmin) {
            $participant = $this->participantService->getParticipantById($id);
            $this->participantService->deleteParticipant($participant);

            $this->addFlash('success', 'The participant was successfully deleted.');
            return $this->redirectToRoute('list');
        }

        return $this->render('home/index.twig', []);
    }

}
