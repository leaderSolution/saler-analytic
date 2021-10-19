<?php

namespace App\Controller;

use App\Repository\VisitRepository;
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
    public function visitsBySalesUser(VisitRepository $visitRep, ChartBuilderInterface $chartBuilder, DateTimeService $dateTimeService): Response
    {
        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        // The amount of sale's user visit per day of current week
        //dd($dateTimeService-> monthsOfThisYear());
        $chart->setData([
            'labels' => ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'],
            'datasets' => [
                [
                    'label' => 'Amount of visits',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $dateTimeService->numVisitsPerDayOfThisWeek($visitRep),
                ],
            ],
        ]);
        $chart->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => 25]],
                ],
            ],
        ]);
        // Chart amount visits by month of current Year
        $chartMonth = $chartBuilder->createChart(Chart::TYPE_BAR_HORIZONTAL);
        $chartMonth->setData([
            'labels' => ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jui', 'aout','Sep', 'Oct', 'Nov', 'Dec'],
            'datasets' => [
                [
                    'label' => 'Amount of visits',
                    'backgroundColor' => 'rgb(255,165,0)',
                    'borderColor' => 'rgb(255,165,0)',
                    'data' => $dateTimeService->numVisitsPerMonthOfThisYear($visitRep),
                ],
            ],
        ]);
        $chartMonth->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => 25]],
                ],
            ],
        ]);

        //
        return $this->render('dashboard/all_clients.html.twig', [
            'chart' => $chart,
            'chartMonth' => $chartMonth,
            'totalVisits' => count($visitRep->findAll()),
            'nbVisits' => $dateTimeService->amountOfNewVisits($visitRep),
        ]);
    }
}
