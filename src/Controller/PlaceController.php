<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use App\Service\PlaceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/place', name: 'place_')]
final class PlaceController extends AbstractController
{
    public function __construct(private readonly PlaceService $placeService)
    {
    }

    #[Route('/', name: 'list')]
    public function list(): Response
    {
        $places = $this->placeService->getAll();
        return $this->render('place/list.html.twig',
            [
                'places' => $places
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
        $place = $id ? $this->placeService->getById($id) : new Place();
        $placeForm = $this->createForm(PlaceType::class, $place);
        $placeForm->handleRequest($request);

        if ($placeForm->isSubmitted() && $placeForm->isValid()) {
            $entityManager->persist($place);
            $entityManager->flush();

            $this->addFlash('success', 'Place créé/modifié avec succès');

            return $this->redirectToRoute('place_list');
        }

        return $this->render('place/add_or_edit.html.twig', [
            'controller_name' => 'PlaceController',
            'place' => $place,
            'form' => $placeForm->createView(),
        ]);
    }

    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request): Response
    {
        $word = $request->query->get('search', '');
        $places = $this->placeService->getAllByWord($word);

        return $this->render('place/list.html.twig', [
            'places' => $places,
            'search' => $word,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['GET'])]
    public function delete(int $id): Response
    {
        $this->placeService->deleteById($id);
        return $this->redirectToRoute('place_list');
    }

}
