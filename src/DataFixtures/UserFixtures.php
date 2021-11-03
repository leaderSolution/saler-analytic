<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{


    const SELLER_REFERENCE = 'Users';
    private $faker;

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
        $this->faker = Factory::create();
    }
    public function load(ObjectManager $manager)
    {

        $user = $this->createUser();
        $admin = $this->createAdmin();


        $manager->persist($admin);
        $manager->persist($user);


        $this->setReference(self::SELLER_REFERENCE, $user);

        $manager->flush();


    }

    public function createUser(): User
    {
        $user = new User();
        $user->setEmail('houssem.trabelssi@amt.tn');
        $user->setFirstname('Houssem');
        $user->setLastname('Trabelssi');
        $user->setRoles(['ROLE_USER']);
        $password = $this->hasher->hashPassword($user, 'reporting');
        $user->setPassword($password);

        return $user;
    }

    public function createAdmin(): User
    {
        $admin = new User();
        $admin->setEmail('imen.admin@gmail.com');
        $admin->setFirstname('Imen');
        $admin->setLastname('Achouri');
        $admin->setRoles(['ROLE_ADMIN']);
        $password = $this->hasher->hashPassword($admin, 'admin21');
        $admin->setPassword($password);

        return $admin;
    }

}
