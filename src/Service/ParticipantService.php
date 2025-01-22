<?php
namespace App\Service;

use App\Entity\Participant;
use App\Repository\ParticipantRepository;

class ParticipantService
{
    private ParticipantRepository $participantRepository;

    public function __construct(
        ParticipantRepository $participantRepository
    ) {
        $this->participantRepository = $participantRepository;
    }

    public function getAllParticipants(): array
    {
        return $this->participantRepository->findAll();
    }

    public function getParticipantById(int $id): ?Participant
    {
        return $this->participantRepository->find($id);
    }

    public function storeOrUpdateParticipant(Participant $participant): void
    {
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

}
