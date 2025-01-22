<?php

namespace App\DataFixtures;

use App\Entity\Site;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SiteFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $sitesFix = [
            ['Name' => 'Rennes'],
            ['Name' => 'Brest']
        ];

        foreach ($sitesFix as $index => $siteData) {
            $site = new Site();
            $site->setName($siteData['Name']);

            $manager->persist($site);
            $this->addReference('site_' . $index, $site);
        }

        $manager->flush();

    }
}
