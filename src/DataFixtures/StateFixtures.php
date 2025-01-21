<?php

namespace App\DataFixtures;

use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StateFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $statesFix = [
            'Créée',
            'Ouverte',
            'Clôturée',
            'Activité en cours',
            'passée',
            'Annulée'
    ];

        foreach ($statesFix as $stateName) {
            $state = new State();
            $state->setName($stateName);

            $manager->persist($state);
        }

        $manager->flush();
    }
}
