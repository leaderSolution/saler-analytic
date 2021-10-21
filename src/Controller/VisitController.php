<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use App\Repository\VisitRepository;
use App\Service\ChartService;
use App\Service\DateTimeService;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[ IsGranted("ROLE_ADMIN"), Route('/admin') ]
class VisitController extends AbstractController
{

    public function __construct(private ChartService $chartService)
    {
    }

    #[Route('/verify-visit', name: 'verify_visit', methods: ['POST'])]
    public function verifyVisit(Request $request, VisitRepository $visitRep): Response
    {
        $isActive = (int) $request->get('isActive');
        $visitID = (int) $request->get('visitID');
        $visit = $visitRep->findOneBy(['id' => $visitID]);
        if ($isActive == 1) {
            $visit->setActive(true);
        }
        if ($isActive == 0) {
            $visit->setActive(false);
        }
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse($visit->getTitle());
    }

    #[Route('/visits/', name: 'all_visits', methods: ['GET', 'POST'])]
    public function allVisits(Request $request, VisitRepository $visitRep): Response
    {
        return $this->renderForm('supervisor/visits.html.twig', [
            'visits' => $visitRep->findAll(),
        ]);
    }

    #[Route('/', name:'admin_dashboard', methods: ['GET', 'POST'] )]
    public function visitsBySalesUser(UserRepository $userRepo,VisitRepository $visitRep,ClientRepository $clientRepo, DateTimeService $dateTimeService): Response
    {
        // The amount of done visits
        $nbDoneVisits = count($visitRep->findBy(['isDone' => true]));
        $sellers = $userRepo->findBy(['isActive' => true], ['id' =>'DESC']);
        $clients = $clientRepo->findBy([],['id'=>'DESC'], 5, 0);

        // The amount of sale's user visit per day of current week
        $chart = $this->chartService->buildChart(Chart::TYPE_BAR,
            $this->chartService::DAYS,
            'Amount of visits',
            'rgb(255, 99, 132)',
            $dateTimeService->numVisitsPerDayOfThisWeek($visitRep));
        // Chart amount visits by month of current Year
        $chartMonth = $this->chartService->buildChart(Chart::TYPE_BAR_HORIZONTAL,
            $this->chartService::MONTHS,
            'Amount of visits',
            'rgb(244,164,96)',
            $dateTimeService->numVisitsPerMonthOfThisYear($visitRep));

        return $this->render('supervisor/dashboard.html.twig', [
            'chart' => $chart,
            'chartMonth' => $chartMonth,
            'nbDoneVisits' => $nbDoneVisits,
            'totalVisits' => count($visitRep->findAll()),
            'totalClients' =>count($clientRepo->findAll()),
            'sellers' =>$sellers,
            'clients' =>$clients,
            'nbVisits' => $dateTimeService->amountOfNewVisits($visitRep),
        ]);
    }
}
