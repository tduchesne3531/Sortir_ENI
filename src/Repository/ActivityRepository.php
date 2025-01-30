<?php

namespace App\Repository;

use App\dto\ActivityFilter;
use App\Entity\Activity;
use App\Entity\Participant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Activity>
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function findAllByFilter(ActivityFilter $filter): array
    {
        $now = (new \DateTime())->modify('-1 month');

        $request = $this->createQueryBuilder('s')
            ->setParameter('dateArchive', $now)
            ->orderBy('s.registrationDeadLine', 'DESC')
            ->where($filter->getArchived()
                ? 's.registrationDeadLine <= :dateArchive'
                : 's.registrationDeadLine > :dateArchive');

        $request
            ->andWhere('(s.state != :state1 OR s.manager = :user)')
            ->setParameter('state1', 1)
            ->setParameter('user', $filter->getUser());

        $request
            ->andWhere($filter->getPast() ? 's.state = :state2' : 's.state != :state2')
            ->setParameter('state2', 5);

//        $request
//            ->join('s.participants', 'p')
//            ->andWhere('(s.state = :state3 OR s.state = :state4)')
//            ->andWhere(':user MEMBER OF s.participants')
//            ->setParameter('state3', 3)
//            ->setParameter('state4', 4)
//            ->setParameter('user', $filter->getUser());

        if ($filter->getSite())
            $request
                ->andWhere('s.site = :site')
                ->setParameter('site', $filter->getSite()->getId());

        if ($filter->getStartDate())
            $request
                ->andWhere('s.dateStartTime >= :dateStartTime1')
                ->setParameter('dateStartTime1', $filter->getStartDate());

        if ($filter->getEndDate())
            $request
                ->andWhere('s.dateStartTime <= :dateStartTime2')
                ->setParameter('dateStartTime2', $filter->getEndDate());

        if ($filter->getOrganizer())
            $request
                ->andWhere('s.manager = :manager')
                ->setParameter('manager', $filter->getUser()->getId());

        if ($filter->getRegistered() && $filter->getUser() instanceof Participant)
            $request
                ->andWhere(':user MEMBER OF s.participants')
                ->setParameter('user', $filter->getRegistered());

        if ($filter->getNotRegistered() && $filter->getUser() instanceof Participant)
            $request
                ->andWhere(':user NOT MEMBER OF s.participants')
                ->setParameter('user', $filter->getNotRegistered());

        return $request->getQuery()->getResult();
    }

}
