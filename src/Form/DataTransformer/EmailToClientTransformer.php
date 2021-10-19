<?php

namespace App\Form\DataTransformer;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EmailToClientTransformer implements DataTransformerInterface
{
    public function __construct(private ClientRepository $clientRepo, private EntityManagerInterface $em)
    {}
    public function transform($value)
    {
        if (null === $value) {
            return '';
        }
        if (!$value instanceof Client) {
            throw new \LogicException('The clientSelectTextType can only be used with client objects');
        }
        return $value->getEmail();
    }

    public function reverseTransform($value)
    {
        $client = $this->clientRepo->findOneBy(['email' => $value]);
        if (!$client) {
            //throw new TransformationFailedException(sprintf('No client found with email "%s"', $value));
            $client = new Client();
            $client->setCodeUniq(rand(1000, 99999));
            $client->setEmail($value);
            $this->em->persist($client);
            $this->em->flush();

        }
        return $client;
    }
}
