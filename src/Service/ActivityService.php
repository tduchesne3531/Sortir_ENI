<?php

namespace App\Service;

use App\Repository\ActivityRepository;

class ActivityService
{

    public function __construct(private readonly ActivityRepository $sortieRepository) {

    }

        public function findById(int $id)
        {
            return $this->sortieRepository->find($id);
        }

    public function getAllIsArchive(bool $isArchive): array
    {
        return $this->sortieRepository->findAllIsArchive($isArchive);
    }

}
