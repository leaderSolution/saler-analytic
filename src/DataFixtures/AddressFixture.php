<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Address;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AddressFixture extends Fixture
{
    public const ADDR_REFERENCE = 'address_record';

    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }
    
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $manager->persist($this->getAddress());
        }
        $manager->flush();
    }

    public function getAddress()
    {
        $address = new Address();
        $address->setCity($this->faker->city());
        $address->setStreet($this->faker->streetAddress());
        $address->setZip($this->faker->postcode());
        $address->setCountry($this->faker->country());
        $this->setReference(self::ADDR_REFERENCE, $address);
        return $address;
    }
}
