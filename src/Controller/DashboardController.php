<?php

namespace App\Controller;

use App\Repository\VisitRepository;
use App\Service\ChartService;
use App\Service\DateTimeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractController
{


    public function __construct(private DateTimeService $timeService, private ChartService $chartService)
    {
    }

    #[Route('/', name: 'dashboard')]
    public function index(VisitRepository $visitRepo): Response
    {
        if($this->getUser())
        {
            if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                return $this->redirectToRoute('admin_dashboard');
            }
        }
        $nbDoneVisits = count($visitRepo->findBy(['user'=>$this->getUser(),'isDone' =>true]));

        $temp = [];
        $visits = $this->getUser()->getVisits();
        foreach ($visits as $visit){
            $temp [] = $visit->getClient();
        }
        $clients = array_unique($temp);
        // The amount of sale's user visit per day of current week
        $chart = $this->chartService->buildChart(Chart::TYPE_BAR,
            $this->chartService::DAYS,
            'Amount of visits',
            'rgb(255, 99, 132)',
            $this->timeService->numSellerVisitsPerDayOfThisWeek($visitRepo, $this->getUser()));
        // Chart amount visits by month of current Year
        $chartMonth = $this->chartService->buildChart(Chart::TYPE_BAR_HORIZONTAL,
            $this->chartService::MONTHS,
            'Amount of visits',
            'rgb(244,164,96)',
            $this->timeService->numSellerVisitsPerMonthOfThisYear($visitRepo, $this->getUser()));



        return $this->render('seller/dashboard.html.twig', [
            'controller_name' => 'DashboardController',
            'chart' => $chart,
            'chartMonth' => $chartMonth,
            'nbVisits' => $this->timeService->amountOfNewVisits($visitRepo),
            'visits' => $visits,
            'clients' => $clients,
            'totalVisits' => count($visits),
            'totalClients' => count($clients),
            'nbDoneVisits' => $nbDoneVisits,

        ]);
    }

    // #[Route('/admin', name: 'admin_dashboard')]
    // public function adminDashboard(): Response
    // {
    //     return $this->render('dashboard/all_clients.html.twig', [
    //         'controller_name' => 'DashboardController',
    //     ]);
    // }
}
