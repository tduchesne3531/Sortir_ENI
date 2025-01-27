<?php

namespace App\Service;

use App\Controller\ActivityController;
use App\Entity\Activity;
use App\Entity\Site;
use App\Repository\SiteRepository;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;

class ActivityService
{

    public function __construct(private readonly ActivityRepository $sortieRepository) {

    }

        public function findById(int $id)
        {
            return $this->sortieRepository->find($id);
        }

    public function getAllArchive(bool $isArchive): array
    {
        return $this->sortieRepository->findAllIsArchive($isArchive);
    }

}
