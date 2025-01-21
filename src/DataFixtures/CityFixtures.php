<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $citiesFix = [
            ['Name' => 'Rennes', "zip" => '35000'],
            ['Name' => 'Lorient', "zip" => '56000'],
            ['Name' => 'Lanion', "zip" => '22000'],
            ['Name' => 'Brest', "zip" => '29000']
        ];

        foreach ($citiesFix as $cityData) {
            $city = new City();
            $city->setName($cityData['Name']);
            $city->setZipCode($cityData['zip']);

            $manager->persist($city);
        }

        $manager->flush();
    }
}
