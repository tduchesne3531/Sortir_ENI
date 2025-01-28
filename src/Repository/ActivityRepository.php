<?php

namespace App\Repository;

use App\Entity\Activity;
use App\Entity\State;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @extends ServiceEntityRepository<Activity>
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function findAllIsArchive(bool $isArchive): array
    {
        return $this->createQueryBuilder('s')
            ->setParameter('dateArchive', (new \DateTime())->modify('-1 month'))
            ->orderBy('s.registrationDeadLine', 'DESC')
            ->where(!$isArchive
                ? 's.registrationDeadLine <= :dateArchive'
                : 's.registrationDeadLine > :dateArchive')
            ->andWhere('s.state != :state')
            ->setParameter('state', 1)
            ->getQuery()->getResult();
    }

}
