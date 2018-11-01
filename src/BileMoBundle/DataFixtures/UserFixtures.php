<?php

namespace BileMoBundle\DataFixtures;

use BileMoBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $userData = [
            [
                'Test',
                '$2y$13$J9SdHWYDXdsJEZYQToRDneDjJTFBtp69X8tQcQJkzK9TJSDpkwa5u',
                'test2',
            ]
        ];

        foreach($userData as list($username, $password, $apiKey))
        {
            $user = new User();
            $user->setUsername($username);
            $user->setPassword($password);
            $user->setApiKey($apiKey);

            $manager->persist($user);
            $manager->flush();
        }
    }
}