<?php

namespace App\Service;

use App\Repository\CityRepository;

class CityService {

    private $cityRepository;

    public function __construct(CityRepository $cityRepository) {
        $this->cityRepository = $cityRepository;
    }

    public function getAll() {
        return $this->cityRepository->findAll();
    }

    public function getById($id) {
        return $this->cityRepository->find($id);
    }

    public function save($city) : void {
        $this->cityRepository->save($city);
    }
}