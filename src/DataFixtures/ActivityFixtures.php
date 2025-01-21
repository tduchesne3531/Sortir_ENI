<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Participant;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActivityFixtures extends Fixture
{
    /**
     * @throws \DateMalformedStringException
     */
    public function load(ObjectManager $manager): void
    {
        $activitiesFix = [
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
        $usersFix = $manager->getRepository(User::class)->findAll();

        foreach ($activitiesFix as $activityFix) {
            $sortie = new Activity();
            $sortie->setName($activityFix['nom']);
            $sortie->setDateStartTime(new \DateTime($activityFix['debut']));
            $sortie->setDuration($activityFix['duree']);
            $sortie->setRegistrationDeadLine(new \DateTime($activityFix['limiteInscription']));
            $sortie->setMaxRegistration($activityFix['nbMax']);
            $sortie->setCreatedBy(new User());

            $manager->persist($sortie);
        }

        $manager->flush();
    }
}
