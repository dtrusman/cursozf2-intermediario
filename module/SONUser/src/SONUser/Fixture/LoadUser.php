<?php

namespace SONUser\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use SONUser\Entity\User;

class LoadUser extends AbstractFixture
{

    public function load(ObjectManager $manager)
    {
        $user = new User;
        $user->setNome("Daniel");
        $user->setEmail("dtrusman@gmail.com");
        $user->setPassword(123456);
        $user->setActive(true);

        $manager->persist($user);

        $manager->flush();
    }
}