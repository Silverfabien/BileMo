<?php

namespace BileMoBundle\DataFixtures;

use BileMoBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    const USER_REFERENCE = 'User';

    public function load(ObjectManager $manager)
    {
        $userData = [
            [
                'Silversat',
                '$2y$13$gVBZJXsro3TVzQSq7LNooe4geAMpkRqduygMol6j.10EbvmBID2w.',
                'hollebeque.fabien@hotmail.com',
                '7c5c5300123d23c3763b53415166ee3b'
            ],
            [
                'Orange',
                '$2y$13$gVBZJXsro3TVzQSq7LNooe4geAMpkRqduygMol6j.10EbvmBID2w.',
                'admin@orange.com',
                'd5773547d4952c5c2cb5c97818559e18'
            ],
            [
                'SFR',
                '$2y$13$gVBZJXsro3TVzQSq7LNooe4geAMpkRqduygMol6j.10EbvmBID2w.',
                'admin@sfr.com',
                'a30ec651dbe208a8a6098a4a06d2a2e5'
            ],
            [
                'BouyguesTelecom',
                '$2y$13$gVBZJXsro3TVzQSq7LNooe4geAMpkRqduygMol6j.10EbvmBID2w.',
                'admin@bouygues.com',
                '61a9379cf31874b94d608337cca16154'
            ],
            [
                'Free',
                '$2y$13$gVBZJXsro3TVzQSq7LNooe4geAMpkRqduygMol6j.10EbvmBID2w.',
                'admin@free.com',
                '09da6a8295edacbca7f41ee859b76722'
            ],
            [
                'LaPosteMobile',
                '$2y$13$gVBZJXsro3TVzQSq7LNooe4geAMpkRqduygMol6j.10EbvmBID2w.',
                'admin@lapostemobile.com',
                'e8b7630d80f6245cacf1453bd7e2ba9f'
            ],
            [
                'NRJMobile',
                '$2y$13$gVBZJXsro3TVzQSq7LNooe4geAMpkRqduygMol6j.10EbvmBID2w.',
                'admin@nrjmobile.com',
                'f71cc9771db9909dab2f7d757be53861'
            ],
            [
                'RedBySFR',
                '$2y$13$gVBZJXsro3TVzQSq7LNooe4geAMpkRqduygMol6j.10EbvmBID2w.',
                'admin@redbysfr.com',
                '7db6c2dca170972320189103b15d451b'
            ],
            [
                'Sosh',
                '$2y$13$gVBZJXsro3TVzQSq7LNooe4geAMpkRqduygMol6j.10EbvmBID2w.',
                'admin@sosh.com',
                '83b800e432f601f9643c2fa164f22e1d'
            ],
            [
                'VirginMobile',
                '$2y$13$gVBZJXsro3TVzQSq7LNooe4geAMpkRqduygMol6j.10EbvmBID2w.',
                'admin@virginmobile.com',
                '5eb5fd169a3b7fc0adebfffcd5797019'
            ]

        ];

        $i = 0;

        foreach($userData as list($username, $password, $email, $apiKey))
        {
            $user = new User();
            $user->setUsername($username);
            $user->setPassword($password);
            $user->setEmail($email);
            $user->setApiKey($apiKey);

            $manager->persist($user);
            $manager->flush();

            $this->addReference(self::USER_REFERENCE . $i, $user);
            $i++;
        }
    }
}