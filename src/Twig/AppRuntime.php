<?php

namespace App\Twig;

use App\Repository\VisitRepository;
use DateTime;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\RuntimeExtensionInterface;


class AppRuntime implements RuntimeExtensionInterface
{

    public const MONTHS = ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jui', 'Août','Sep', 'Oct', 'Nov', 'Dec'];

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

    /**
     * @return int|null
     */
    public function sellerNbClients(): int
    {
        $clients = $this->security->getUser()->getClients();

        return count($clients);
    }

    /**
     * @param $year
     * @return int
     */
    function lastWeekOfYear($year): int
    {
        $date = new DateTime;
        $date->setISODate($year, 53);
        return ($date->format("W") === "53" ? 53 : 52);
    }


    /**
     * @return string[]
     */
    public function months(): array
    {
        return self::MONTHS;
    }
}