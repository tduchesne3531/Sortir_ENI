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
            ['nom' => 'Sortie archivé organisateur site Rennes', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 0, 'place' => 0, 'site' => '0', 'etat' => 5, 'participants' => [1, 2]],
            ['nom' => 'Sortie passé organisateur site Rennes', 'debut' => '2025-01-27', 'duree' => 120, 'limiteInscription' => '2025-01-20', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 0, 'place' => 1, 'site' => '0', 'etat' => 5, 'participants' => [1]],
            ['nom' => 'Sortie cloturé organisateur site Rennes', 'debut' => '2025-01-31', 'duree' => 120, 'limiteInscription' => '2025-01-20', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 0, 'place' => 1, 'site' => '0', 'etat' => 3, 'participants' => [1, 2]],
            ['nom' => 'Sortie en cours organisateur site Rennes', 'debut' => '2025-01-29', 'duree' => 6000, 'limiteInscription' => '2025-01-28', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 0, 'place' => 2, 'site' => '0', 'etat' => 4, 'participants' => [1]],
            ['nom' => 'Sortie annulée organisateur site Rennes', 'debut' => '2025-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 0, 'place' => 2, 'site' => '0', 'etat' => 6, 'participants' => [1, 2]],
            ['nom' => 'Sortie future site Rennes', 'debut' => '2025-02-03', 'duree' => 120, 'limiteInscription' => '2025-02-01', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 0, 'place' => 2, 'site' => '0', 'etat' => 2, 'participants' => [1, 2]],
            ['nom' => 'Sortie archivé non organisateur site Rennes', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 1, 'place' => 0, 'site' => '0', 'etat' => 5, 'participants' => [2]],
            ['nom' => 'Sortie passé non organisateur site Rennes', 'debut' => '2025-01-27', 'duree' => 120, 'limiteInscription' => '2025-01-20', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 1, 'place' => 1, 'site' => '0', 'etat' => 5, 'participants' => [2, 3]],
            ['nom' => 'Sortie cloturé non organisateur site Rennes', 'debut' => '2025-01-31', 'duree' => 120, 'limiteInscription' => '2025-01-20', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 1, 'place' => 1, 'site' => '0', 'etat' => 3, 'participants' => [2]],
            ['nom' => 'Sortie en cours non organisateur site Rennes', 'debut' => '2025-01-29', 'duree' => 6000, 'limiteInscription' => '2025-01-28', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 1, 'place' => 2, 'site' => '0', 'etat' => 4, 'participants' => [2, 3]],
            ['nom' => 'Sortie annulée non organisateur site Rennes', 'debut' => '2025-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 1, 'place' => 2, 'site' => '0', 'etat' => 6, 'participants' => [2]],
            ['nom' => 'Sortie future site Rennes', 'debut' => '2025-02-03', 'duree' => 120, 'limiteInscription' => '2025-02-01', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 1, 'place' => 2, 'site' => '0', 'etat' => 2, 'participants' => [1, 2]],

            ['nom' => 'Sortie archivé organisateur site Brest', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 0, 'place' => 0, 'site' => '1', 'etat' => 5, 'participants' => []],
            ['nom' => 'Sortie passé organisateur site Brest', 'debut' => '2025-01-27', 'duree' => 120, 'limiteInscription' => '2025-01-20', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 0, 'place' => 1, 'site' => '1', 'etat' => 5, 'participants' => []],
            ['nom' => 'Sortie cloturé organisateur site Brest', 'debut' => '2025-01-30', 'duree' => 120, 'limiteInscription' => '2025-01-20', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 0, 'place' => 1, 'site' => '1', 'etat' => 3, 'participants' => []],
            ['nom' => 'Sortie en cours organisateur site Brest', 'debut' => '2025-01-28', 'duree' => 6000, 'limiteInscription' => '2025-01-28', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 0, 'place' => 2, 'site' => '1', 'etat' => 4, 'participants' => []],
            ['nom' => 'Sortie annulée organisateur site Brest', 'debut' => '2025-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 0, 'place' => 2, 'site' => '1', 'etat' => 6, 'participants' => []],
            ['nom' => 'Sortie future site Brest', 'debut' => '2025-02-03', 'duree' => 120, 'limiteInscription' => '2025-02-01', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 0, 'place' => 2, 'site' => '0', 'etat' => 2, 'participants' => [1, 2]],
            ['nom' => 'Sortie archivé non organisateur site Brest', 'debut' => '2024-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 1, 'place' => 0, 'site' => '1', 'etat' => 5, 'participants' => []],
            ['nom' => 'Sortie passé non organisateur site Brest', 'debut' => '2025-01-27', 'duree' => 120, 'limiteInscription' => '2025-01-20', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 1, 'place' => 1, 'site' => '1', 'etat' => 5, 'participants' => []],
            ['nom' => 'Sortie cloturé non organisateur site Brest', 'debut' => '2025-01-30', 'duree' => 120, 'limiteInscription' => '2025-01-20', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 1, 'place' => 1, 'site' => '1', 'etat' => 3, 'participants' => []],
            ['nom' => 'Sortie en cours non organisateur site Brest', 'debut' => '2025-01-28', 'duree' => 6000, 'limiteInscription' => '2025-01-28', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 1, 'place' => 2, 'site' => '1', 'etat' => 4, 'participants' => []],
            ['nom' => 'Sortie annulée non organisateur site Brest', 'debut' => '2025-10-01', 'duree' => 120, 'limiteInscription' => '2024-09-01', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 1, 'place' => 2, 'site' => '1', 'etat' => 6, 'participants' => []],
            ['nom' => 'Sortie future site Brest', 'debut' => '2025-02-03', 'duree' => 120, 'limiteInscription' => '2025-02-01', 'nbMax' => 5, 'info' => 'info', 'organisteur' => 1, 'place' => 2, 'site' => '0', 'etat' => 2, 'participants' => [1, 2]],
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
            $activity->setCreatedAt(new \DateTimeImmutable());
            $activity->setUpdatedAt(new \DateTime());
            foreach ($activityData['participants'] as $participant) {
                $activity->addParticipant($this->getReference('participant_' . $participant, Participant::class));
            }

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
