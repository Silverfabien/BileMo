<?php

namespace BileMoBundle\DataFixtures;

use BileMoBundle\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ClientFixtures extends Fixture implements DependentFixtureInterface
{
    private $characters = 'abcdefghijklmnopqrstuvwxyz';
    private $country = ['France', 'Angleterre', 'Allemagne', 'Italie', 'Espagne', 'Suéde', 'Autriche', 'Suisse', 'Belgique', 'Finlande'];
    private $adress = ['Rue', 'Avenue', 'Chemin', 'Allée', 'Ruelle'];

    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 100; $i++) {
            $client = new Client();
            $client->setUser($this->getReference(UserFixtures::USER_REFERENCE . rand(0,8)));
            $client->setFullName(substr(str_shuffle($this->characters), 0, rand(5, 20)));
            $client->setEmail(substr(str_shuffle($this->characters), 0, rand(5, 20)). '@hotmail.com');
            $client->setCountry($this->country[rand(0,9)]);
            $client->setCity(rand(5,20));
            $client->setAddress(random_int(0,2000) .' '. $this->adress[rand(0,4)] .' '. substr(str_shuffle($this->characters), 0, rand(5, 20)));
            $client->setCreated(new \DateTime());

            $manager->persist($client);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}