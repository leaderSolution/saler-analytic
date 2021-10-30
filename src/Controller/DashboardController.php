<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\ClientRepository;
use App\Repository\VisitRepository;
use App\Service\ChartService;
use App\Service\DateTimeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractController
{


    public function __construct(private DateTimeService $timeService, private EntityManagerInterface $em ,private ChartService $chartService)
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
    #[Route('/profile/{id}', name: 'profile_user', methods: ['POST','GET'])]
    public function profile(Request $request, User $user, ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findAll();
        $form = $this->createForm(UserType::class, $user);
        $form->remove('isActive');
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $this->em->flush();
            return $this->redirectToRoute('profile_user', ['id' =>$user->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'clients' => $clients,
            'allClients' => count($clientRepository->findAll()),
        ]);
    }

     #[Route('/last-num-week', name: 'last_num_week')]
     public function lastNumWeek(Request $request): JsonResponse
     {
         $current = new \DateTime('now');
         $currentYear = $current->format('Y');
         $currentWeek = $current->format('W');
         if(null === $request->get('year')){
             $number = $this->timeService->getIsoWeeksInYear($currentYear);
         }else {
             $number = $this->timeService->getIsoWeeksInYear($request->get('year'));
         }

         return new JsonResponse(['lastWeek' => $number, 'thisWeek' =>$currentWeek]);
     }
}
