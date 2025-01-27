<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\User;
use App\Form\ParticipantType;
use App\Service\ParticipantService;
use App\Utils\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/participant/', name: 'participant_')]
final class ParticipantController extends AbstractController
{
    private participantService $participantService;

    public function __construct(ParticipantService $participantService)
    {
        $this->participantService = $participantService;
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $participants = $this->participantService->getAllParticipants();

        return $this->render('participant/list.html.twig', [
            'participants' => $participants,
        ]);
    }

    #[Route('detail/{id}', name: 'detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id, Participant $participant, #[CurrentUser] ?User $user): Response
    {
//        $mode = 'read';

        if ($user) {
            return $this->render('participant/detail.html.twig', [
                'participant' => $participant,
//                'mode' => $mode,
            ]);
        }
        return $this->redirectToRoute('app_home');
    }

    #[Route('add', name: 'add', methods: ['GET', 'POST'])]
    public function addParticipant(Request $request, FileUploader $fileUploader): Response {
        $mode = 'add';
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $participant = new Participant();
        $participantForm = $this->createForm(ParticipantType::class, $participant);
        $participantForm->handleRequest($request);

        if ($participantForm->isSubmitted() && $participantForm->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $participantForm->get('plainPassword')->getData();

            $user = $this->getUser();

            $participant->setRoles(($participantForm->has('isAdmin') && $participantForm->get('isAdmin')->getData())
                ? ['ROLE_ADMIN']
                : ['ROLE_USER']);

            /** @var UploadedFile|null $uploadedFile */
            $uploadedFile = $participantForm->get('photo')->getData();

            $newFilePath = null;
            if ($uploadedFile) {
                $newFilePath = $fileUploader->upload($uploadedFile, $participant->getPseudo(), 'participants');
            }

            $this->participantService->storeOrUpdateParticipant($participant, $plainPassword, $newFilePath, $user);
            $this->addFlash('success', 'The participant' . $participant->getFirstname() . 'was successfully created.');


            return $this->redirectToRoute('participant_detail', ['id' => $participant->getId()]);
        }
        return $this->render('participant/add_or_edit.html.twig', [
            'mode' => $mode,
            'participantForm' => $participantForm,
        ]);
    }

    #[Route('edit/{id}', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function editAccount(
        int $id,
        Request $request,
        #[CurrentUser] ?User $user,
        FileUploader $fileUploader
    ): Response {
        $mode = 'edit';
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        if ($isAdmin || $user->getId() === $id) {
            $participant = $this->participantService->getParticipantById($id);
            if (!$participant) {
                throw $this->createNotFoundException('Participant not found.');
            }
            $participantForm = $this->createForm(ParticipantType::class, $participant, [
                'is_edit_mode' => true,
            ]);
            $participantForm->handleRequest($request);

            if ($participantForm->isSubmitted() && $participantForm->isValid()) {
                /** @var string $plainPassword */
                $plainPassword = $participantForm->get('plainPassword')->getData();

                /** @var UploadedFile|null $uploadedFile */
                $uploadedFile = $participantForm->get('photo')->getData();

                if ($uploadedFile) {
                    if ($participant->getPhoto()) {
                        $fileUploader->remove($participant->getPhoto());
                    }
                    $newFilePath = $fileUploader->upload($uploadedFile, $participant->getPseudo(), 'participants');
                }

                $this->participantService->storeOrUpdateParticipant($participant, $plainPassword, $newFilePath ?? null, $user);

                $this->addFlash('success', 'The participant' . $participant->getFirstname() . 'was successfully edited.');
                return $this->redirectToRoute('participant_detail', ['id' => $participant->getId()]);
            }

            return $this->render('participant/add_or_edit.html.twig', [
                'mode' => $mode,
                'participant' => $participant,
                'participantForm' => $participantForm,
            ]);
        }
        return $this->redirectToRoute('app_home');
    }

    #[Route('delete/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function deleteParticipant(int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $participant = $this->participantService->getParticipantById($id);
        $this->participantService->deleteParticipant($participant);

        $this->addFlash('success', 'The participant was successfully deleted.');
        return $this->redirectToRoute('participant_list');
    }

    #[Route('disable/{id}', name: 'disable', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function disableOrEnableParticipant(int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $participant = $this->participantService->getParticipantById($id);
        $this->participantService->disableOrEnableParticipant($participant);

        $this->addFlash('success', 'The participant was successfully updated.');
        return $this->redirectToRoute('participant_detail', ['id' => $participant->getId()]);
    }


}
