<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UsersFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $nino = new User();
        $nino->setUsername('Nino');
        $nino->setPassword('$2y$13$kI6t/b/Szb54i/9zjP2Ej.dlQZpjJ4B.beiEvWakuizSWYgstnbCS');

        $mateo = new User();
        $mateo->setUsername('Mateo');
        $mateo->setPassword('$2y$13$ER5pGI4BLPhgeA14kW05Y.cmkUJ5xAbTj3YJ1uB.aC7qUjUFs6g3u');

        $cedric = new User();
        $cedric->setUsername('Cedric');
        $cedric->setPassword('$2y$13$obR8w6NaflDx0Kxq3375.uP97od/X/6I/78601ufPfmVwar.HEAwe');


        $admin = new User();
        $admin->setUsername('admin');
        $admin->setPassword('$2y$13$iNPdikESfu4Kfck2jS5xn.tkasMInQ75xrgwdefnqyXw3nQGmUOJq');
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);
        $manager->persist($nino);
        $manager->persist($cedric);
        $manager->persist($mateo);

        $manager->flush();
    }
}
