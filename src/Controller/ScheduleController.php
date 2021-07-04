<?php

namespace App\Controller;

use App\Entity\Visit;
use App\Form\VisitType;
use App\Repository\VisitRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ScheduleController extends AbstractController
{
    public const INVALID_DATE = 'Invalid date';

    #[Route('/schedule', name: 'schedule')]
    public function index(Request $request): Response
    {
        $visit = new Visit();
        $visit->setStartTime(new \DateTime());
        $visit->setEndTime(new \DateTime());
        $form = $this->createForm(VisitType::class, $visit);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $visit = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($visit);
            $entityManager->flush();

            return $this->redirectToRoute('schedule');
        }

        return $this->render('schedule/index.html.twig', ['form' => $form->createView()]);
    }
    
    #[Route('/user/schedule', name: 'user_schedule')]
    public function getUserSchedule(VisitRepository $visitRepository): JsonResponse
    {
        $visits = $visitRepository->findAll();

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
                'allDay' => $visit->getAllDay()


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

        if (self::INVALID_DATE !== $startAt) {
            $visit->setStartTime(new DateTime($startAt));
        }
        if (self::INVALID_DATE !== $endAt) {
            $visit->setEndTime(new DateTime($endAt));
        }
        
        $this->getDoctrine()->getManager()->flush();

        return  new JsonResponse($visit);
    }

    #[Route('/profile', name: 'profile')]
    public function profile(): Response
    {
        return $this->render('profile/index.html.twig');
    }
}
