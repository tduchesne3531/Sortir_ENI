<?php

namespace App\Service;

use App\Entity\Activity;
use App\Entity\State;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method getEntityManager()
 */
class StateService
{
    private StateRepository $stateRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(StateRepository $stateRepository, ManagerRegistry $registry)
    {
        $this->stateRepository = $stateRepository;
        $this->entityManager = $registry->getManager();
    }


    /**
     * Checks and updates the state of activities.
     *
     * @param Activity[] $activities An array of Activity objects.
     * @return Activity[] An array of Activity objects with updated states.
     */
    public function verfiAndChange(array $activities): array
    {
        $now = new \DateTime();
        foreach ($activities as $activity) {
            if ($activity->getState()->getId() === 2 && ($activity->getRegistrationDeadLine() < $now))
                $activity->setState($this->stateRepository->find(3));
            if ($activity->getState()->getId() === 3 && $activity->getDateStartTime() < $now)
                $activity->setState($this->stateRepository->find(4));
            if ($activity->getState()->getId() === 4 && ($activity->getDateStartTime()->getTimestamp() + ($activity->getDuration() * 60)) <= $now->getTimestamp()) {
                $activity->setState($this->stateRepository->find(5));
            }
        }
        $this->entityManager->flush();

        return $activities;
    }
}