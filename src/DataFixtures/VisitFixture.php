<?php

namespace App\DataFixtures;

use DateInterval;
use Faker\Factory;
use App\Entity\Visit;
use DateTimeInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class VisitFixture extends Fixture
{
    private $faker;
    
    public function __construct()
    {
        $this->faker = Factory::create();
    }
    
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 15; $i++) {
            $manager->persist($this->getVisit());
        }
        $manager->flush();
    }

    public function getVisit()
    {
        $visit = new Visit();
        $visit->setTitle($this->faker->sentence());
        $visit->setStartTime($this->faker->dateTimeThisYear());
        /** @var DateTime */
        $sdt = $visit->getStartTime();
        /** @var DateTimeInterface */
        $endTime = $sdt->modify('+1 H');
        $visit->setEndTime($endTime);
        $visit->setClient($this->getReference(ClientFixture::CLIENT_REFERENCE));
        return $visit;
    }
}
