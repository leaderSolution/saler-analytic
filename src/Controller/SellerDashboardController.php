<?php

namespace App\Controller;

use App\DataManager\SellerDataManager;
use App\Entity\Visit;
use App\Form\VisitType;
use App\Repository\ClientRepository;
use App\Repository\VisitRepository;
use App\Repository\LeaveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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

    #[Route('/clients-list', name: 'seller_clients_list', methods: ['POST', 'GET'])]
    public function clientsList(Request $request, VisitRepository $visitRepo, ClientRepository $clientRepo): Response
    {
        $clients = [];
        $data = $request->get('data');
        foreach ($data as $item){
            $clients [] = $clientRepo->findOneBy(['codeUniq'=>$item]);
        }

        return $this->render('seller/clientsTab.html.twig', ['clients' => $clients ]);

    }

    #[Route('/visits-quarter', name: 'seller_visits_quarter', methods: ['POST', 'GET'])]
    public function createQuarterCard(Request $request, VisitRepository $visitRepo, ClientRepository $clientRepo): Response
    {
        $data = [];
        $data = $this->sellerDataManager->sellerVisitsByQuarter($request, $visitRepo,$clientRepo, $this->getUser());
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
        $data = $this->sellerDataManager->visitsOfTheMonth($parameters['period'], $parameters['year'], $visitRepo, $leaveRepo ,$this->getUser());

        return new JsonResponse($data);
    }


    #[Route('/visits/edit/{idC}/{id}', name: 'visit_edit', methods: ['GET', 'POST'])]
    public function editVisit(Request $request, Visit $visit, ClientRepository $clientRepo): Response
    {
        $client = $clientRepo->findOneBy(['id'=>$request->get('idC')]);
        $form = $this->createForm(VisitType::class, $visit);
        $form->add('isDone',CheckboxType::class, [
            'label'    => 'Completed ?',
            'required' => false,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $visit->setUser($this->getUser());
            $visit->setClient($client);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('seller_client_visits', ['id'=>$client->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('seller/edit_visit.html.twig', [
            'visit' => $visit,
            'form' => $form,
        ]);
    }

    #[Route('/clients', name: 'seller_clients', methods: ['GET', 'POST'])]
    public function sellerClients(ClientRepository $clientRepository): Response
    {
        $clients = null;
        if ($this->getUser()) {
            $clients = $clientRepository->findBy(['user' => $this->getUser(), 'isProspect'=>null ,'isProspect'=>false]);
        }
        return $this->render('seller/clients.html.twig', ['clients' => $clients ]);
    }

    #[Route('/client/{id}/visits', name: 'seller_client_visits', methods: ['GET', 'POST'])]
    public function sellerClientVisits(Request $request, ClientRepository $clientRepository): Response
    {
        $visits = null;
        $client= $clientRepository->findOneBy(['id'=>$request->get('id'), 'user' =>$this->getUser()]);
        if(! is_null($client)){
            $visits = $client->getVisits();
        }

        return $this->render('seller/visits.html.twig', ['visits' => $visits ]);
    }

    #[Route('/turnover-month', name: 'seller_turnover_month', methods: ['POST', 'GET'])]
    public function createTurnoverMonthChart(Request $request, VisitRepository $visitRepo): Response
    {
        $parameters = $this->sellerDataManager->sendRequestedParameters($request,'month','m');
        $data = $this->sellerDataManager->turnoverPerMonthOfYear($parameters['period'], $parameters['year'], $visitRepo, $this->getUser());

        return new JsonResponse($data);
    }
}
