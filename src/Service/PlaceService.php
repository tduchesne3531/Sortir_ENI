<?php

namespace App\Service;

use App\Repository\PlaceRepository;

class PlaceService
{
    private PlaceRepository $placeRepository;

    /**
     * @param PlaceRepository $placeRepository
     */
    public function __construct(PlaceRepository $placeRepository)
    {
        $this->placeRepository = $placeRepository;
    }

    public function getAll(): array
    {
        return $this->placeRepository->findAll();
    }
}
