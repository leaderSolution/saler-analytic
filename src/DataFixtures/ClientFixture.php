<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ClientFixture extends Fixture
{
    public const CLIENT_REFERENCE = 'client';

    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $manager->persist($this->getClient());
        }
        $manager->flush();
    }

    public function getClient()
    {
        $client = new Client();
        $client->setCodeUniq($this->faker->unique()->numberBetween(1000, 9000));
        $client->setEmail($this->faker->email());
        //$client->addAddress($this->getReference(AddressFixture::ADDR_REFERENCE));
        $this->setReference(self::CLIENT_REFERENCE, $client);
        return $client;
    }
}
