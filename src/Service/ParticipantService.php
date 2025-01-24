<?php
namespace App\Service;

use App\Entity\Participant;
use App\Entity\User;
use App\Repository\ParticipantRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ParticipantService
{
    private ParticipantRepository $participantRepository;
    private UserPasswordHasherInterface $userPasswordHasher;
    public function __construct(
        ParticipantRepository $participantRepository,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        $this->participantRepository = $participantRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function getAllParticipants(): array
    {
        return $this->participantRepository->findAll();
    }

    public function getParticipantById(int $id): ?Participant
    {
        return $this->participantRepository->find($id);
    }

    public function storeOrUpdateParticipant(
        Participant $participant,
        ?string $plainPassword,
        User $user
    ): void
    {
        if (!empty($plainPassword)) {
            $participant->setPassword($this->userPasswordHasher->hashPassword($participant, $plainPassword));
        }
        if ($participant->isActive() === null) {
            $participant->setIsActive(true);
        }
        if ($participant->getCreatedBy() === null) {
            $participant->setCreatedBy($user);
        } else {
            $participant->setUpdatedBy($user);
        }

        $this->participantRepository->save($participant);
    }

//    if needed later for job specs
//    public function updateParticipant(Participant $participant): void
//    {
//        $this->participantRepository->save($participant);
//    }

    public function deleteParticipant(Participant $participant): void
    {
        $this->participantRepository->delete($participant);
    }

    public function disableOrEnableParticipant(Participant $participant): void
    {
        $participant->isActive() ?
            $participant->setIsActive(false) :
            $participant->setIsActive(true);

        $this->participantRepository->save($participant);
    }
}
