<?php

namespace App\Service;

use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;

class PlaceService
{
    private PlaceRepository $placeRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(PlaceRepository $placeRepository, EntityManagerInterface $entityManager)
    {
        $this->placeRepository = $placeRepository;
        $this->entityManager = $entityManager;
    }

    public function getAll(): array
    {
        return $this->placeRepository->findAll();
    }

    public function getById(int $id) {
        return $this->placeRepository->find($id);
    }

    public function deleteById(int $id): void
    {
        $place = $this->placeRepository->find($id);
        if(!$place)
            throw new \RuntimeException("Place with id $id not found.");

        $this->entityManager->remove($place);
        $this->entityManager->flush();
    }

    public function getAllByWord(string $word): array
    {
        return $this->placeRepository->createQueryBuilder('s')
            ->where('LOWER(s.name) LIKE LOWER(:word)')
            ->setParameter('word', '%' . strtolower($word) . '%')
            ->getQuery()
            ->getResult();
    }

}
