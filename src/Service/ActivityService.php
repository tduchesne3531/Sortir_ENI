<?php

namespace App\Service;

use App\dto\ActivityFilter;
use App\Entity\Participant;
use App\Repository\ActivityRepository;

class ActivityService
{

    public function __construct(private readonly ActivityRepository $sortieRepository) {

    }

        public function getById(int $id)
        {
            return $this->sortieRepository->find($id);
        }

    public function getAllByFilter(ActivityFilter $filter): array
    {
        return $this->sortieRepository->findAllByFilter($filter);
    }

}
