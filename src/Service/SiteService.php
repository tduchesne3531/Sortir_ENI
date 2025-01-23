<?php

namespace App\Service;

use App\Entity\Site;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;

class SiteService
{
    private SiteRepository $siteRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(SiteRepository $siteRepository, EntityManagerInterface $entityManager)
    {
        $this->siteRepository = $siteRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Récupère tous les sites
     *
     * @return Site[]
     */
    public function findAllSites(): array
    {
        return $this->siteRepository->findAll();
    }

    public function getById(int $idSite): Site {
        return $this->siteRepository->find($idSite);
    }

    /**
     * Enregistre un nouveau site
     *
     * @param Site $site
     */
    public function store(Site $site): void
    {
       $this->siteRepository->save($site);
    }

    /**
     * Met à jour un site existant
     *
     * @param int $id
     * @param Site $updatedSite
     * @return void
     */
    public function update(int $id) : void
    {
        $site = $this->siteRepository->find($id);
        $this->siteRepository->save($site);
    }

    /**
     * Supprime un site
     *
     * @param int $id
     * @return void
     */
    public function deleteById(int $id): void
    {
        $site = $this->siteRepository->find($id);
        if (!$site)
            throw new \RuntimeException("Site with id $id not found.");

        $this->entityManager->remove($site);
        $this->entityManager->flush();
    }

    public function getAllByWord(string $word): array
    {
        return $this->siteRepository->createQueryBuilder('s')
            ->where('LOWER(s.name) LIKE LOWER(:word)')
            ->setParameter('word', '%' . strtolower($word) . '%')
            ->getQuery()
            ->getResult();
    }

}