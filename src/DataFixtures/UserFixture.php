<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public const USER_REFERENCE = 'User';

    private $faker;

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
        $this->faker = Factory::create();
    }
    public function load(ObjectManager $manager)
    {
        $manager->persist($this->getUser());
        
        $manager->flush();
    }

    public function getUser()
    {
        $user = new User();
        $user->setEmail('commercial@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $password = $this->hasher->hashPassword($user, 'reporting');
        $user->setPassword($password);
        $this->setReference(self::USER_REFERENCE, $user);
        return $user;
    }
}
