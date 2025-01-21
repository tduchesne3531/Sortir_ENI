<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

//        $presidents = [
//            'Adolphe Thiers',
//            'Patrice de Mac Mahon',
//            'Jules Grévy',
//            'Sadi Carnot',
//            'Jean Casimir-Perier',
//            'Félix Faure',
//            'Émile Loubet',
//            'Armand Fallières',
//            'Raymond Poincaré',
//            'Paul Deschanel',
//        ];
//
//        foreach ($presidents as $index => $presidentName) {
//
//        $user = new User();
//        $user->setPseudo($presidentName);
//        $user->setEmail(strtolower(str_replace(' ', '.', $presidentName)) . '@mail.fr');
//        $user->setPassword($this->passwordHasher->hashPassword($user, 'root'));
//
//        // Adolphe Thiers - Admin
//        if ($index === 0) {
//            $user->setRoles(['ROLE_ADMIN']);
//            $user->setIsAdmin(true);
//        } else {
//            $user->setRoles(['ROLE_USER']);
//            $user->setIsAdmin(false);
//        }
//
//        $manager->persist($user);
//    }
//
//        $manager->flush();

    }
}
