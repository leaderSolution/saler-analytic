<?php

namespace App\DataFixtures;

use App\Repository\ClientRepository;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use App\Entity\Visit;
use DateTimeInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Security;

class VisitFixtures extends Fixture
{
    private $faker;
    
    
    public function __construct(private Security $security, private EntityManagerInterface $em)
    {
        $this->faker = Factory::create();
    }
    
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++) {
            $manager->persist($this->getVisit());
        }
        $manager->flush();
    }

    public function getVisit()
    {
        $visit = new Visit();
        $visit->setUser($this->getReference(UserFixtures::SELLER_REFERENCE));
        $visit->setTitle($this->faker->sentence());
        $visit->setStartTime($this->faker->dateTimeBetween($startDate = '-4 years', $endDate = 'now', $timezone = null));
        $visit->setBackgroundColor($this->faker->rgbCssColor());
        $visit->setBorderColor($this->faker->rgbCssColor());
        $visit->setTextColor($this->faker->hexcolor());
        $visit->setAllDay($this->faker->boolean());
        /** @var DateTime */
        $sdt = $visit->getStartTime();
        /** @var DateTimeInterface */
        $endTime = $sdt->modify('+1 H');
        $visit->setEndTime($endTime);
        $visit->setClient($this->getReference(ClientFixtures::CLIENT_REF));

        return $visit;
    }
}
