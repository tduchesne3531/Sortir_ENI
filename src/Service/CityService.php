<?php

namespace App\Service;

use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CityService
{

    private CityRepository $cityRepository;
    private HttpClientInterface $httpClient;
    private EntityManagerInterface $entityManager;

    /**
     * @param CityRepository $cityRepository
     * @param HttpClientInterface $httpClient
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(CityRepository $cityRepository, HttpClientInterface $httpClient, EntityManagerInterface $entityManager)
    {
        $this->cityRepository = $cityRepository;
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
    }


    public function getAll()
    {
        return $this->cityRepository->findAll();
    }

    public function getById($id)
    {
        return $this->cityRepository->find($id);
    }

    public function save($city): void
    {
        $this->cityRepository->save($city);
        $this->entityManager->remove($city);
        $this->entityManager->flush();
    }

    public function deleteById(int $id): void
    {
        $city = $this->cityRepository->find($id);
        if (!$city) {
            throw new \RuntimeException("City with id $id not found.");
        }

        $this->entityManager->remove($city);
        $this->entityManager->flush();
    }

    public function getAllByWord(string $word): array
    {
        return $this->cityRepository->createQueryBuilder('s')
            ->where('LOWER(s.name) LIKE LOWER(:word)')
            ->setParameter('word', '%' . strtolower($word) . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère la liste des communes pour un département donné.
     *
     * @param string $departmentCode Code du département (ex: "01")
     * @return array Liste des communes (décodées en tableau PHP)
     * @throws TransportExceptionInterface
     * @throws \Exception
     * @throws DecodingExceptionInterface
     */
    public function getCommunesByDepartment(string $departmentCode): array
    {
        $client = HttpClient::create([
            'proxy' => '10.35.0.248:8080',
            'timeout' => 20,
            'verify_peer' => false,
            'verify_host' => false,
        ]);
        $url = sprintf('https://geo.api.gouv.fr/departements/%s/communes', $departmentCode);
        $response = $client->request('GET', $url);

        if ($response->getStatusCode() !== 200)
            throw new \Exception('Erreur lors de la récupération des communes.');

        $communes = $response->toArray();
        $result = [];
        foreach ($communes as $commune) {
            $result[] = [
                'nom' => $commune['nom'],
                'code_postal' => $commune['codesPostaux'][0]
            ];
        }

        return $result;
    }

}
