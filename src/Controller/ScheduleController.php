<?php

namespace App\Controller;

use DateTime;
use App\Entity\Visit;
use App\Entity\Leave;
use App\Form\VisitType;
use App\Repository\ClientRepository;
use App\Repository\VisitRepository;
use App\Repository\LeaveRepository;
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
    public function getUserSchedule(): JsonResponse
    {
        $visits = $this->getUser()->getVisits();
        $leaves = $this->getUser()->getLeaves();

        $pickedVisits = [];

        foreach ($visits as $visit) {
            $pickedVisits [] = [
                'id' => $visit->getId(),
                'start' => $visit->getStartTime()->format('Y-m-d H:i:s'),
                'end' => $visit->getEndTime()->format('Y-m-d H:i:s'),
                'title' => $visit->getTitle()." | ".$visit->getClient()->getDesignation(),
                'backgroundColor' => $visit->getBackgroundColor(),
                'borderColor' => $visit->getBackgroundColor(),
                'textColor' => 'white',
                'allDay' => $visit->getAllDay(),
                'isDone' => $visit->getIsDone(),


            ];
        }
        foreach ($leaves as $leave) {
            $pickedVisits [] = [
                'id' => $leave->getId(),
                'start' => $leave->getStartAt()->format('Y-m-d'),
                'end' => $leave->getEndAt()->format('Y-m-d'),
                'title' => $leave->getLabel(),
                'backgroundColor' => 'red',
                'borderColor' => 'red',
                'textColor' => 'white',
                


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
            $visit->setTitle(strtok($title, '|'));
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
        
        return $this->render('seller/visits.html.twig', ['visits' => $visits ]);
    }

    // pick up a leave on the calendar
    #[Route('/leave', name: 'new_leave', methods:['POST'])]
    public function pickupLeave(Request $request): JsonResponse
    {
        $leave = new Leave();
        $leave->setUser($this->getUser());
        $leave->setLabel('Congé');
        $startAt = $request->get('start');
        $endAt = $request->get('end');

        if (self::INVALID_DATE !== $startAt) {
            $leave->setStartAt(new DateTime($startAt));
        }
        if (self::INVALID_DATE !== $endAt) {
            $leave->setEndAt(new DateTime($endAt));
        }
        $this->getDoctrine()->getManager()->persist($leave);
        $this->getDoctrine()->getManager()->flush();

        return  new JsonResponse($leave);
    }


}
