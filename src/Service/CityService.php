<?php

namespace App\Service;

use App\Repository\CityRepository;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CityService {

    private CityRepository $cityRepository;
    private HttpClientInterface $httpClient;

    public function __construct(CityRepository $cityRepository, HttpClientInterface $httpClient) {
        $this->cityRepository = $cityRepository;
        $this->httpClient = $httpClient;
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

    /**
     * Récupère la liste des communes pour un département donné.
     *
     * @param string $departmentCode Code du département (ex: "01")
     * @return array Liste des communes (décodées en tableau PHP)
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function getCommunesByDepartment(string $departmentCode): array
    {
        $url = sprintf('https://geo.api.gouv.fr/departements/%s/communes', $departmentCode);
        $response = $this->httpClient->request('GET', $url);

        if ($response->getStatusCode() !== 200)
            throw new \Exception('Erreur lors de la récupération des communes.');

        return $response->toArray();
    }
}