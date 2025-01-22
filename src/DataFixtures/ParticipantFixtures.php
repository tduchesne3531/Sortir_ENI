<?php

namespace App\DataFixtures;

use App\Entity\Participant;
use App\Entity\Place;
use App\Entity\Site;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ParticipantFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $participantsFix = [
            ['pseudo'=> 'athiers', 'firstname' => 'Adolphe', 'lastname' => 'Thiers', 'phone' => '0102030405', 'site' => 0],
            ['pseudo'=> 'pmahon', 'firstname' => 'Patrice', 'lastname' => 'de Mac Mahon', 'phone' => '0203040506', 'site' => 0],
            ['pseudo'=> 'jgrevy', 'firstname' => 'Jules', 'lastname' => 'Grévy', 'phone' => '0304050607', 'site' => 0],
            ['pseudo'=> 'scarnot', 'firstname' => 'Sadi', 'lastname' => 'Carnot', 'phone' => '0405060708', 'site' => 0],
            ['pseudo'=> 'jperrier', 'firstname' => 'Jean', 'lastname' => 'Casimir-Perier', 'phone' => '0506070809', 'site' => 0],
            ['pseudo'=> 'ffaure', 'firstname' => 'Félix', 'lastname' => 'Faure', 'phone' => '0607080901', 'site' => 1],
            ['pseudo'=> 'eloubet', 'firstname' => 'Émile', 'lastname' => 'Loubet', 'phone' => '0708090102', 'site' => 1],
            ['pseudo'=> 'afallieres', 'firstname' => 'Armand', 'lastname' => 'Fallières', 'phone' => '0809010203', 'site' => 1],
            ['pseudo'=> 'rpoincare', 'firstname' => 'Raymond', 'lastname' => 'Poincaré', 'phone' => '0901020304', 'site' => 1],
            ['pseudo'=> 'pdeschanel', 'firstname' => 'Paul', 'lastname' => 'Deschanel', 'phone' => '1002030405', 'site' => 1]
        ];

        foreach ($participantsFix as $index => $participantData) {
            $participant = new Participant();

            $participant->setFirstname($participantData['firstname']);
            $participant->setLastname($participantData['lastname']);
            $participant->setPhone($participantData['phone']);
            $participant->setPseudo($participantData['pseudo']);
            $participant->setEmail(strtolower($participantData['firstname'] . '.' . $participantData['lastname']) . '@mail.fr');
            $participant->setPassword($this->passwordHasher->hashPassword($participant, 'root'));
            $participant->setIsActive(true);
            $participant->setCreatedAt(new \DateTimeImmutable('now'));
            $participant->setSite($this->getReference('site_' . $participantData['site']));

            // Adolphe Thiers - Admin
            if ($index === 0) {
                $participant->setRoles(['ROLE_ADMIN']);
                $participant->setIsAdmin(true);
            } else {
                $participant->setRoles(['ROLE_USER']);
                $participant->setIsAdmin(false);
            }

            $this->addReference('participant_' . $index, $participant);
            $manager->persist($participant);
        }

        $manager->flush();
    }
}
