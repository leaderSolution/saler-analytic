<?php

namespace App\Controller;

use App\DataManager\SellerDataManager;
use App\Repository\VisitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/seller')]
class SellerDashboardController extends AbstractController
{


    public function __construct(private SellerDataManager $sellerDataManager)
    {
    }

    #[Route('/visits-quarter', name: 'seller_visits_quarter', methods: ['POST', 'GET'])]
    public function createQuarterCard(Request $request, VisitRepository $visitRepo): Response
    {
        $data = [];
        $data = $this->sellerDataManager->sellerVisitsByQuarter($request, $visitRepo, $this->getUser());
        return new JsonResponse($data);
    }

    #[Route('/visits-week', name: 'seller_visits_week', methods: ['POST', 'GET'])]
    public function createChart(Request $request, VisitRepository $visitRepo): Response
    {
        $parameters = $this->sellerDataManager->sendRequestedParameters($request,'week','W');
        $data = $this->sellerDataManager->visitsPerDayOfWeek($parameters['period'], $parameters['year'],$visitRepo, $this->getUser());

        return new JsonResponse($data);
    }
    #[Route('/visits-month', name: 'seller_visits_month', methods: ['POST', 'GET'])]
    public function createMonthChart(Request $request, VisitRepository $visitRepo): Response
    {
        $parameters = $this->sellerDataManager->sendRequestedParameters($request,'month','m');
        $data = $this->sellerDataManager->visitsPerMonthOfYear($parameters['period'], $parameters['year'], $visitRepo, $this->getUser());

        return new JsonResponse($data);
    }
    #[Route('/visits-of-the-month', name: 'seller_visits_the_month', methods: ['POST', 'GET'])]
    public function createSelectedMonthChart(Request $request, VisitRepository $visitRepo): Response
    {
        $parameters = $this->sellerDataManager->sendRequestedParameters($request,'month','m');
        $data = $this->sellerDataManager->visitsOfTheMonth($parameters['period'], $parameters['year'], $visitRepo, $this->getUser());

        return new JsonResponse($data);
    }


}
