<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Participant;
use App\Entity\Place;
use App\Entity\Site;
use App\Entity\State;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ActivityFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws \DateMalformedStringException
     */
    public function load(ObjectManager $manager): void
    {
        $activitiesFix = [
            ['nom' => 'Sortie 1', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'organisteur' => 0, 'place' => 0, 'site' => '0', 'etat' => 0],
            ['nom' => 'Sortie 2', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'organisteur' => 0, 'place' => 1, 'site' => '0', 'etat' => 0],
            ['nom' => 'Sortie 3', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'organisteur' => 0, 'place' => 1, 'site' => '0', 'etat' => 0],
            ['nom' => 'Sortie 4', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'organisteur' => 0, 'place' => 2, 'site' => '0', 'etat' => 0],
            ['nom' => 'Sortie 5', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'organisteur' => 0, 'place' => 2, 'site' => '0', 'etat' => 0],
            ['nom' => 'Sortie 6', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'organisteur' => 1, 'place' => 3, 'site' => '1', 'etat' => 0],
            ['nom' => 'Sortie 7', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'organisteur' => 1, 'place' => 4, 'site' => '1', 'etat' => 0],
            ['nom' => 'Sortie 8', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'organisteur' => 1, 'place' => 5, 'site' => '1', 'etat' => 0],
            ['nom' => 'Sortie 9', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'organisteur' => 1, 'place' => 6, 'site' => '1', 'etat' => 0],
            ['nom' => 'Sortie 10', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 2, 'info' => 'info', 'organisteur' => 1, 'place' => 7, 'site' => '1', 'etat' => 0]
        ];

        foreach ($activitiesFix as $index => $activityData) {
            $activity = new Activity();
            $activity->setName($activityData['nom']);
            $activity->setDateStartTime(new \DateTime($activityData['debut']));
            $activity->setDuration($activityData['duree']);
            $activity->setRegistrationDeadLine(new \DateTime($activityData['limiteInscription']));
            $activity->setMaxRegistration($activityData['nbMax']);
            $activity->setSite($this->getReference('site_' . $activityData['site'], Site::class));
            $activity->setState($this->getReference('state_' . $activityData['etat'], State::class));
            $activity->setManager($this->getReference('participant_' . $activityData['organisteur'], Participant::class));
            $activity->setPlace($this->getReference('place_' . $activityData['place'], Place::class));

            $manager->persist($activity);
            $this->addReference('activity_' . $index, $activity);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SiteFixtures::class,
            StateFixtures::class,
            ParticipantFixtures::class,
            PlaceFixtures::class
        ];
    }


}
