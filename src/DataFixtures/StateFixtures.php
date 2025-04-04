<?php

namespace App\DataFixtures;

use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StateFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $statesFix = [
            ['Name' => 'Créée'],
            ['Name' => 'Ouverte'],
            ['Name' => 'Clôturée'],
            ['Name' => 'Activité en cours'],
            ['Name' => 'passée'],
            ['Name' => 'Annulée']
    ];

        foreach ($statesFix as $index => $stateData) {
            $state = new State();
            $state->setName($stateData['Name']);
            $state->setCreatedAt(new \DateTimeImmutable());
            $state->setUpdatedAt(new \DateTime());

            $manager->persist($state);
            $this->addReference('state_' . ($index+1), $state);
        }

        $manager->flush();

    }
}
