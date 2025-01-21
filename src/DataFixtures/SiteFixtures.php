<?php

namespace App\DataFixtures;

use App\Entity\Site;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SiteFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $sitesFix = [
            'Rennes',
            'Brest'
        ];

        foreach ($sitesFix as $name) {
            $site = new Site();
            $site->setName($name);

            $manager->persist($site);
        }

        $manager->flush();
    }
}
