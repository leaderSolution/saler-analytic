<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ClientFixtures extends Fixture
{
    private $faker;

    public const CLIENT_REF = 'client';

    public function __construct(private Security $security)
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $fileName = "Houssem-CA-xlsx-Houssem-1-61951eb3cdd37.csv";
        $inputFile = '/var/www/sellerAnalytic/public/uploads/CSVFiles/'.$fileName;
        $decoder = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $rows = $decoder->decode(file_get_contents($inputFile), 'csv');
        foreach ($rows as $key=>$row){
            $client = new Client();
            $client->setCodeUniq($row['codeUniq']);
            $client->setEmail('exemple'.$key.'@gmail.com');
            $client->setDesignation($row['designation']);
            $client->setUser($this->getReference(UserFixtures::SELLER_REFERENCE));
            $client->setRegion($row['region']);
            $row['turnover'] = preg_replace('/[^A-Za-z0-9]/', "", $row['turnover']);
            $client->setTurnover((float)$row['turnover']);
            $client->setIsProspect(false);
            $manager->persist($client);
            $this->setReference(self::CLIENT_REF, $client);
        }
        $manager->flush();
    }


}