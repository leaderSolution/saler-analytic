<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/client')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'client_index', methods: ['GET'])]
    public function index(Request $request, ClientRepository $clientRepository): Response
    {
        $searchTerm = $request->query->get('q');

        $clients = $clientRepository->search($searchTerm);

        if ($request->query->get('preview')) {
            return $this->render('client/_searchPreview.html.twig', [
                'clients' => $clients,
            ]);
        }

        return $this->render('schedule/_form.html.twig', [
            'clients' => $clients,
            'searchTerm' => $searchTerm
        ]);
    }
    #[Route('/list', name: 'get_clients', methods: ['GET'])]
    public function getClients(Request $request, ClientRepository $clientRepository): JsonResponse
    {
        $clients = [];
        foreach ($clientRepository->findAll() as $client) {
            $clients [] = $client->getEmail();
        }
        return new JsonResponse($clients);
    }
    #[Route('/new', name: 'client_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/admin/all', name: 'all_clients', methods: ['GET'])]
    public function allClients(ClientRepository $clientRepo): Response
    {
       return $this->render('supervisor/all_clients.html.twig', [
            'clients' => $clientRepo->findAll(),
        ]);
    }

    #[Route('/admin/show-chart-goal-clients', name: 'show_chart_goal_clients', methods: ['GET','POST'])]
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

    #[Route('/admin/{id}/edit', name: 'client_edit', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
    }
}
