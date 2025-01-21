<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Participant;
use App\Entity\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlaceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $placesFixtures = [
            ['name' => 'lieu 1', 'adresse' => 'rue 1', 'latitude' => '11.11', 'longitude' => '11.11', 'city' => 1],
            ['name' => 'lieu 2', 'adresse' => 'rue 2', 'latitude' => '22.22', 'longitude' => '22.22', 'city' => 1],
            ['name' => 'lieu 3', 'adresse' => 'rue 3', 'latitude' => '33.33', 'longitude' => '33.33', 'city' => 2],
            ['name' => 'lieu 4', 'adresse' => 'rue 4', 'latitude' => '44.44', 'longitude' => '44.44', 'city' => 2],
            ['name' => 'lieu 5', 'adresse' => 'rue 5', 'latitude' => '55.55', 'longitude' => '55.55', 'city' => 3],
            ['name' => 'lieu 6', 'adresse' => 'rue 6', 'latitude' => '66.66', 'longitude' => '66.66', 'city' => 3],
            ['name' => 'lieu 7', 'adresse' => 'rue 7', 'latitude' => '77.77', 'longitude' => '77.77', 'city' => 4],
            ['name' => 'lieu 8', 'adresse' => 'rue 8', 'latitude' => '88.88', 'longitude' => '88.88', 'city' => 4]
        ];
        $citiesFix = $manager->getRepository(City::class)->findAll();

        foreach ($placesFixtures as $placeFix) {
            $place = new Place();
            $place->setName($placeFix['name']);
            $place->setAdresse($placeFix['adresse']);
            $place->setLatitude($placeFix['latitude']);
            $place->setLongitude($placeFix['longitude']);
            $place->setCity($citiesFix -> get($placeFix['city']));

            $manager->persist($place);
        }

        $manager->flush();
    }
}
