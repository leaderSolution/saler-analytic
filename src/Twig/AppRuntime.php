<?php

namespace App\Twig;

use App\Repository\VisitRepository;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\RuntimeExtensionInterface;

class AppRuntime implements RuntimeExtensionInterface
{


    /**
     * @param VisitRepository $visitRepo
     * @param Security        $security
     */
    public function __construct(private VisitRepository $visitRepo, private Security $security)
    {
    }

    /**
     * @return array
     */
    public function sellerNotification(): array
    {
        $now = new \DateTime('now');
        return $this->visitRepo->findSellerUnvisitedClient($now->format('Y-m-d H:i'), $this->security->getUser());
    }
}