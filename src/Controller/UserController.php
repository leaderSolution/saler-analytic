<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use App\Repository\VisitRepository;
use App\Service\DateTimeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[  IsGranted("ROLE_ADMIN"), 
    Route('/admin/user')
    ]
class UserController extends AbstractController
{

    /***
     * @param DateTimeService $dateTimeService
     * @param EntityManagerInterface $em
     */
    public function __construct(private DateTimeService $dateTimeService, private EntityManagerInterface $em)
    {
    }

    #[Route('/', name: 'user_index', methods: ['GET', 'POST'])]
    public function index(UserRepository $userRepository, Request $request): Response
    {
        $users = [];
        $template = $request->query->get('ajax') ? '_list.html.twig' : 'index.html.twig';
        
        $all = $userRepository->findBy([],['id' => 'DESC']);
        foreach ($all as $item){
            if(! in_array('ROLE_ADMIN',$item->getRoles())){
                $users [] = $item;
            }
        }

        return $this->render('user/'.$template, [
            'users' => $users,
        ]);
    }

    #[Route('/new', name:'user_new', methods:['POST', 'GET'])]
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            
            if ($request->isXmlHttpRequest()) {
                return new Response(null, 204);
            }
            return $this->redirectToRoute('user_index');

        }
        $template = $request->isXmlHttpRequest() ? '_form.html.twig' : 'new.html.twig';

        return $this->render('user/' . $template, [
            'user' => $user,
            'form' => $form->createView(),
        ], new Response(
            null,
            $form->isSubmitted() && !$form->isValid() ? 422 : 200,
        ));
    }

    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->remove('password');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if the password is inserted
            /*if(null !== $form->getData()->getPassword()){
                $password = $hasher->hashPassword($user, $form->getData()->getPassword());
                $user->setPassword($password);
            }*/

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('supervisor/edit_seller.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/visits/{id}', name: 'user_visits', methods: ['GET', 'POST'])]
    public function userVisits(Request $request, User $user,ChartBuilderInterface $chartBuilder,ClientRepository $clientRepo,VisitRepository $visitRepository): Response
    {
        $labels = [];
        $nbVisitsPerClient = [];
        $clients = [];
        // Get the visited clients by the seller (User with 'ROLE_USER')
        $visits = $user->getVisits();
        if( ! empty($visits)){
            foreach ($visits as $visit){
                $clients [] = $visit->getClient();
            }
        }
        // Find the seller's new visits
        $toDay = new \DateTime('now');
        $newVisits = count($visitRepository->findSellerNewVisits($toDay->format('Y-m-d'),$user));
        // Find the seller's coming up visits
        $comingUpVisits = count($visitRepository->findSellerComingUpVisits($toDay->format('Y-m-d'), $user));
        // Find the seller done visits
        $sellerDoneVisits = count($visitRepository->findSellerDoneVisits($user));
        // Find the seller visits by period (Day, Week, Month, Year)
        // TODO Find the seller visits by period (Day, Week, Month, Year)
        $sellerVisitsPerDay = $this->dateTimeService->numSellerVisitsPerDayOfThisWeek($visitRepository, $user);
        //dd($sellerVisitsPerDay);
        // Amount of visits per client
        $results = $visitRepository->findSellerVisitsByClient($user->getId());
        foreach ($results as $item){
            $client = $clientRepo->findOneBy(['id' => $item['idC']]);
            $labels [] = $client->getCodeUniq();
            $nbVisitsPerClient [] = $item['nbVisits'];
        }
        //dd($nbVisitsPerClient);
        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        // The amount of sale's user visit per day of current week
        //dd($dateTimeService-> monthsOfThisYear());
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Amount of visits Per client',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $nbVisitsPerClient,
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

        return $this->renderForm('supervisor/visits_seller.html.twig', [
            'visits' => $user->getVisits(),
            'clients' => array_slice(array_unique($clients),0,4),
            'nbNewVisits' => $newVisits,
            'comingUpVisits' => $comingUpVisits,
            'sellerDoneVisits' => $sellerDoneVisits,
            'chart' => $chart,
            'seller' => $user
        ]);
    }
    

    #[Route('/users/', name: 'all_users', methods: ['GET', 'POST'])]
    public function allUsers(Request $request, UserRepository $userRep): Response
    {
        return $this->render('dashboard.html.twig', [
            'users' => $userRep->findAll(),
        ]);
    }
    #[Route('/profile/{id}', name: 'profile', methods: ['POST','GET'])]
    public function profile(Request $request, User $user, ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findBy([],['id'=>'DESC'], 4, 0);
        $form = $this->createForm(UserType::class, $user);
        $form->remove('isActive');
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $this->em->flush();
            return $this->redirectToRoute('profile', ['id' =>$user->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'clients' => $clients,
            'allClients' => count($clientRepository->findAll()),
        ]);
    }

    #[Route('/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }



}
