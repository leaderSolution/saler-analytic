<?php

namespace App\Controller;

use DateTime;
use App\Entity\Visit;
use App\Form\VisitType;
use App\Repository\ClientRepository;
use App\Repository\VisitRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ScheduleController extends AbstractController
{
    public const INVALID_DATE = 'Invalid date';

    #[Route('/schedule', name: 'schedule')]
    public function index(Request $request, ClientRepository $clientRepository): Response
    {
        $visit = new Visit();
        $visit->setStartTime(new \DateTime());
        $visit->setEndTime(new \DateTime());
        $visit->setUser($this->getUser());
        $form = $this->createForm(VisitType::class, $visit);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $visit = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($visit);
            $entityManager->flush();

            if ($request->isXmlHttpRequest()) {
                return new Response(null, 204);
            }

            return $this->redirectToRoute('schedule');
        }
        $searchTerm = $request->query->get('q');

        
        $clients = $clientRepository->search($searchTerm, $this->getUser());

        if ($request->query->get('preview')) {
            return $this->render('client/_searchPreview.html.twig', [
                'clients' => $clients,
            ]);
        }
        $template = $request->isXmlHttpRequest() ? '_form.html.twig' : 'index.html.twig';

        return $this->render('schedule/' . $template, [
            'form' => $form->createView(),'searchTerm' => $searchTerm
        ], new Response(
            null,
            $form->isSubmitted() && !$form->isValid() ? 422 : 200,
        ));
    }
    
    #[Route('/user/schedule', name: 'user_schedule')]
    public function getUserSchedule(VisitRepository $visitRepository): JsonResponse
    {
        $visits = $this->getUser()->getVisits();

        $pickedVisits = [];

        foreach ($visits as $visit) {
            $pickedVisits [] = [
                'id' => $visit->getId(),
                'start' => $visit->getStartTime()->format('Y-m-d H:i:s'),
                'end' => $visit->getEndTime()->format('Y-m-d H:i:s'),
                'title' => $visit->getTitle(),
                'backgroundColor' => $visit->getBackgroundColor(),
                'borderColor' => $visit->getBorderColor(),
                'textColor' => $visit->getTextColor(),
                'allDay' => $visit->getAllDay(),
                'isDone' => $visit->getIsDone(),


            ];
        }

        return  new JsonResponse($pickedVisits);
    }

    // edit a visit on the calendar
    #[Route('/edit/{id}', name: 'edit_schedule', methods:['POST'])]
    public function editSchedule(Request $request, VisitRepository $visitRepository): JsonResponse
    {
        $visit = $visitRepository->findOneBy(['id' => $request->get('id')]);
        $visitData =  json_encode($visit);
        $startAt = $request->get('startTime');
        $endAt = $request->get('endTime');
        $title = $request->get('title');
        $isDone = $request->get('isDone');

        if($isDone == 'true'){
            $visit->setIsDone(true);
        }
        if($isDone == 'false'){
            $visit->setIsDone(false);
        }
        if (null !== $title) {
            $visit->setTitle($title);
        } else {
            if (self::INVALID_DATE !== $startAt) {
                $visit->setStartTime(new DateTime($startAt));
            }
            if (self::INVALID_DATE !== $endAt) {
                $visit->setEndTime(new DateTime($endAt));
            }
        }
        
        
        
        $this->getDoctrine()->getManager()->flush();

        return  new JsonResponse($isDone);
    }

    #[Route('/visits', name: 'visit_index', methods: ['GET', 'POST'])]
    public function visits(VisitRepository $v): Response
    {
        $visits = null;

        if ($this->getUser()) {
            $visits = $this->getUser()->getVisits();
        }
        
        return $this->render('seller/visits.html.twig', ['visites' => $visits ]);
    }

    #[Route('/visits/edit/{id}', name: 'visit_edit', methods: ['GET', 'POST'])]
    public function editVisit(Request $request, Visit $visit): Response
    {
        $form = $this->createForm(VisitType::class, $visit);
        $form->add('isDone',CheckboxType::class, [
            'label'    => 'Completed ?',
            'required' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('visit_index', [], Response::HTTP_SEE_OTHER);
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
            $clients = $clientRepository->findBy(['user' => $this->getUser()]);
        }

        return $this->render('seller/clients.html.twig', ['clients' => $clients ]);
    }


    #[Route('/delete-visit', name: 'delete_visit', methods: ['POST'])]
    public function sellerDeleteVisit(Request $request, VisitRepository $visitRep): Response
    {

        $visitID = (int) $request->get('visitID');
        $visit = $visitRep->findOneBy(['id' => $visitID]);
        $this->getDoctrine()->getManager()->remove($visit);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse($visit->getTitle());
    }
}
