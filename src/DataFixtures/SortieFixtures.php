<?php

namespace App\DataFixtures;

use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SortieFixtures extends Fixture
{
    /**
     * @throws \DateMalformedStringException
     */
    public function load(ObjectManager $manager): void
    {
        $sortiesFix = [
            ['nom' => 'Sortie 1', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'etat' => 1],
            ['nom' => 'Sortie 2', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'etat' => 1],
            ['nom' => 'Sortie 3', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'etat' => 1],
            ['nom' => 'Sortie 4', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'etat' => 1],
            ['nom' => 'Sortie 5', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'etat' => 1],
            ['nom' => 'Sortie 6', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'etat' => 1],
            ['nom' => 'Sortie 7', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'etat' => 1],
            ['nom' => 'Sortie 8', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'etat' => 1],
            ['nom' => 'Sortie 9', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'etat' => 1],
            ['nom' => 'Sortie 10', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'etat' => 1],
            ['nom' => 'Sortie 11', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'etat' => 1],
        ];
        $participantsFix = $manager->getRepository(Participant::class)->findAll();

        foreach ($sortiesFix as $sortieFix) {
            $sortie = new Sortie();
            $sortie->setName($sortieFix['nom']);
            $sortie->setDateStartTime(new \DateTime($sortieFix['debut']));
            $sortie->setDuration($sortieFix['duree']);
            $sortie->setMaxRegistration(new \DateTime($sortieFix['limiteInscription']));
            $sortie->setMaxRegistration($sortieFix['nbMax']);
            $sortie->setCreatedUser($sortieFix[$participantsFix->get(0)]);

            $manager->persist($sortie);
        }

        $manager->flush();
    }
}
