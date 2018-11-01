<?php

namespace BileMoBundle\DataFixtures;

use BileMoBundle\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PhoneFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $phoneData = [
            [
                'Galaxy S9+',
                'Samsung',
                'Android 8.0',
                '64Go / 128Go / 256Go',
                'Bleu / Noir / Gris / Violet',
                'La description du Galaxy S9+ à mettre ici ...',
                '700',
                '6',
            ],
            [
                'Galaxy S9',
                'Samsung',
                'Android 8.0',
                '64Go / 128Go / 256Go',
                'Blanc / Noir / Gris / Violet',
                'La description du Galaxy S9 à mettre ici ...',
                '600',
                '2',
            ],
            [
                'Iphone X',
                'Apple',
                'IOS 11',
                '64Go / 256Go',
                'Argent / Gris sidéral',
                'La description de l\Iphone X à mettre ici ...',
                '980',
                '12',
            ],
            [
                'Iphone 8 Plus',
                'Apple',
                'IOS 11',
                '64Go / 256Go',
                'Argent / Gris sidéral / Or',
                'La description de l\'Iphone 8 Plus à mettre ici ...',
                '680',
                '5',
            ]
        ];

        foreach($phoneData as list($name, $mark, $systemVersion, $memory, $color, $description, $price, $quantity))
        {
            $phone = new Phone();
            $phone->setName($name);
            $phone->setMark($mark);
            $phone->setSystemVersion($systemVersion);
            $phone->setMemory($memory);
            $phone->setColor($color);
            $phone->setDescription($description);
            $phone->setPrice($price);
            $phone->setQuantity($quantity);

            $manager->persist($phone);
            $manager->flush();
        }
    }
}