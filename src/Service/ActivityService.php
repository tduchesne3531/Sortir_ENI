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

    public function getAllActivities() : array {
        return $this->sortieRepository->findAll();
    }

//    public function getAllBySite(Site $site) : array {
//        return $this->sortieRepository->findBy(['site' => $site], ['date' => 'DESC']);
//    }

    public function findById(int $id)
    {
        return $this->sortieRepository->find($id);
    }

    public function create(Activity $activity): void  {
        $this->sortieRepository->save($activity);
    }

    public function update(Activity $activity): void {
        $this->sortieRepository->save($activity);
    }

    public function delete(Activity $activity): void {
        $this->sortieRepository->delete($activity);
    }
}
