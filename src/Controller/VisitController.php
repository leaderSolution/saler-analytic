<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use App\Repository\VisitRepository;
use App\Service\ChartService;
use App\Service\DateTimeService;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
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

    public function __construct(private ChartService $chartService, private EntityManagerInterface $em)
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

    #[Route('/client/all', name: 'all_clients', methods: ['GET'])]
    public function allClients(ClientRepository $clientRepo): Response
    {
        return $this->render('supervisor/all_clients.html.twig', [
            'clients' => $clientRepo->findAll(),
        ]);
    }

    #[Route('/client/show-chart-goal-clients', name: 'show_chart_goal_clients', methods: ['GET','POST'])]
    public function showChartGoalClients(Request $request, ClientRepository $clientRepo): JsonResponse
    {
        $goals = [];
        $nbV = 0;
        $nbVisits = [];
        $dataNbV = [];
        $periodP = '';
        $periodR = '';
        $dataClient = [];
        $temp = 0;
        $year = $request->get('year');
        $now = new \DateTime('now');
        $clients = $clientRepo->findAll();

        $period = ['Day','Week','Month','Year'];

        foreach ($period as $key=>$p){

            foreach ($clients as $client) {
                $dataClient [] = $client->getCodeUniq();


                if($client->getGoals()->isEmpty()){
                    $periodP =$p;
                    $temp = 0;
                }else {

                    foreach ($client->getGoals() as $goal){

                        if(($key+1) == $goal->getPeriod()){
                            $periodP =$p;
                            $goal->getCreatedAt()->format('Y') === $year ? $temp = $goal->getNbVisits() : $temp = 0 ;
                        }


                    }

                }
                $nbVisits[] = $temp;
                $temp = 0;
            }
            $goals [] = (object) ["name" => $periodP, "data" => $nbVisits];
            $nbVisits = [];

        }
        return new JsonResponse([$key,'categories' => array_unique($dataClient), 'Series' => $goals]);

    }

    #[Route('/client/import-csv', name: 'import_csv', methods: ['POST'])]
    public function importCSVFile(Request $request, UserRepository $userRepo, ClientRepository $clientRepo ,FileUploader $uploader, LoggerInterface $logger): Response
    {
        $token = $request->get("token");

        if (!$this->isCsrfTokenValid('upload', $token))
        {
            $logger->info("CSRF failure");

            return new Response("Operation not allowed",  Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);
        }

        $file = $request->files->get('csvFile');

        if (empty($file))
        {
            return new Response("No file specified",
                Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }

        $fileName = $uploader->upload($file);
        $inputFile = $this->getParameter('csvFiles_directory').'/'.$fileName;
        $decoder = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $rows = $decoder->decode(file_get_contents($inputFile), 'csv');
        foreach ($rows as $key=>$row){
            $seller = $userRepo->findOneBy(['email' => $row['seller']]);
            $client = new Client();
            $client->setCodeUniq($row['codeUniq']);
            $client->setEmail('exemple'.$key.'@gmail.com');
            $client->setDesignation($row['designation']);
            $client->setUser($seller);
            $client->setRegion($row['region']);
            $client->setTurnover((float)$row['turnover']);
            $this->em->persist($client);
        }
        $this->em->flush();

        return $this->redirectToRoute('all_clients', [], Response::HTTP_SEE_OTHER);

    }

    #[Route('/client/{id}/edit', name: 'client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(! empty($form->getData()->getGoals())){
                foreach ($form->getData()->getGoals() as $goal){
                    $goal->setClient($client);
                }
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('all_clients', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('supervisor/edit_client.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

}
