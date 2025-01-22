<?php

namespace App\mapper;

use App\dto\ActivityDto;
use App\Entity\Activity;
use App\Entity\Participant;

final class ActivityMapper {

    public function toDto(Activity $activity, Participant $participant): ActivityDto {
        $dto = new ActivityDto();
        $dto->setId($activity->getId());
        $dto->setName($activity->getName());
        $dto->setDateStartTime($activity->getDateStartTime());
        $dto->setDuration($activity->getDuration());
        $dto->setRegistrationDeadLine($activity->getRegistrationDeadLine());
        $dto->setInscrits(count($activity->getParticipants()));
        $dto->setMaxRegistration($activity->getMaxRegistration());
        $dto->setDescription($activity->getDescription());
        $dto->setState($activity->getState()->getName());
        $dto->setIncrit($participant->getSorties()->contains($activity));
        $dto->setOrganisateur(($participant->getSortiesManaged()->contains($activity))
            ? $participant->getFirstname() . ' ' . mb_substr($participant->getLastname(), 0, 1). '.'
        : $activity->getManager()->getPseudo());

        return $dto;
    }
}