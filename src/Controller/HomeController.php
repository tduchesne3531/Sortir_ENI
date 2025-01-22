<?php

namespace App\Controller;

use App\Entity\Site;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        $sitesFix = [
            ['Name' => 'Rennes'],
            ['Name' => 'Brest']
        ];

        foreach ($sitesFix as $index => $siteData) {
            $site = new Site();
            $site->setName($siteData['Name']);

          //  $manager->persist($site);
          //  $this->addReference('site_' . $index, $site);
            dd($index);
        }
        return $this->render('home.html.twig');

    }
}
