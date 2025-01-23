<?php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Activity>
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function save(Activity $activity) : void
    {
        $this->getEntityManager()->persist($activity);
        $this->getEntityManager()->flush();
    }

    public function delete(Activity $activity) : void
    {
        $this->getEntityManager()->remove($activity);
        $this->getEntityManager()->flush();
    }

}
