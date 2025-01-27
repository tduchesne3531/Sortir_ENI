<?php

namespace App\Repository;

use App\Entity\Activity;
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

    public function findAllIsArchive(bool $isArchive) : array {
        $dateArchive = (new \DateTime())->modify('-1 month');

        $queryBuilder = $this->sortieRepository->createQueryBuilder('s');

        if ($isArchive)
            $queryBuilder->where('s.registrationDeadLine <= :dateArchive');
        else
            $queryBuilder->where('s.registrationDeadLine > :dateArchive');

        return $queryBuilder
            ->setParameter('dateArchive', $dateArchive)
            ->orderBy('s.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
