<?php
namespace App\Service;

use App\Entity\Participant;
use App\Entity\Site;
use App\Repository\ParticipantRepository;
use App\Repository\SiteRepository;

class ParticipantService
{
    private ParticipantRepository $participantRepository;
    private SiteRepository $siteRepository;

    public function __construct(
        ParticipantRepository $participantRepository,
        SiteRepository $siteRepository,
    ) {
        $this->participantRepository = $participantRepository;
        $this->siteRepository = $siteRepository;
    }

    public function getAllParticipants(): array
    {
        return $this->participantRepository->findAll();
    }

    public function getParticipantById(int $id): ?Participant
    {
        return $this->participantRepository->find($id);
    }

    public function storeOrUpdateParticipant(Participant $participant, Site $site): void
    {
        $this->siteRepository->save($site);
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
