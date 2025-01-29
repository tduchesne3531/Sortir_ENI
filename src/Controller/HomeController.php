<?php

namespace App\Controller;

use App\Entity\Site;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home', methods: ['GET'])]
    #[Route('/', name: 'app_home_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->redirectToRoute('activity_list');
    }
}
