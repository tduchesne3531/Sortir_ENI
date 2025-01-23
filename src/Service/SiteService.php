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
    public function update(int $id, Site $site): void
    {
        $this->siteRepository->update($site);
    }

    /**
     * Supprime un site
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $site = $this->siteRepository->find($id);
        if (!$site) {
            throw new \RuntimeException("Site with id $id not found.");
        }

        $this->entityManager->remove($site);
        $this->entityManager->flush();
    }

}