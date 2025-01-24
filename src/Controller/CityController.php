<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use App\Service\CityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/city', name: 'city_')]
final class CityController extends AbstractController
{

    private CityService $cityService;

    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(): Response
    {
        $cities = $this->cityService->getAll();
        return $this->render('city/list.html.twig',
            [
                'cities' => $cities
            ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function addOrEdit(
        Request                $request,
        EntityManagerInterface $entityManager,
        int $id = null
    ): Response
    {
        $city = $id ? $this->cityService->getById($id) : new City();
        $cityForm = $this->createForm(CityType::class, $city);
        $cityForm->handleRequest($request);

        if ($cityForm->isSubmitted() && $cityForm->isValid()) {
            $city->setCreatedAt(new \DateTimeImmutable());
            $city->setUpdatedAt(new \DateTime());

            $entityManager->persist($city);
            $entityManager->flush();

            $this->addFlash('success', 'Ville créé/modifié avec succès');

            return $this->redirectToRoute('city_list');
        }

        return $this->render('city/add_or_edit.html.twig', [
            'controller_name' => 'CityController',
            'city' => $city,
            'form' => $cityForm->createView(),
        ]);
    }

    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request): Response
    {
        $word = $request->query->get('search', '');
        $cities = $this->cityService->getAllByWord($word);

        return $this->render('city/list.html.twig', [
            'cities' => $cities,
            'search' => $word,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['GET'])]
    public function delete(int $id): Response
    {
        $this->cityService->deleteById($id);
        return $this->redirectToRoute('site_list');
    }


}
