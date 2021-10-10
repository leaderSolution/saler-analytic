<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\VisitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[  IsGranted("ROLE_ADMIN"), 
    Route('/admin/user')
    ]
class UserController extends AbstractController
{
    #[Route('/', name: 'user_index', methods: ['GET', 'POST'])]
    public function index(UserRepository $userRepository, Request $request): Response
    {
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
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/visits/{id}', name: 'user_visits', methods: ['GET', 'POST'])]
    public function userVisits(Request $request, User $user): Response
    {
        return $this->renderForm('user/visits.html.twig', [
            'visits' => $user->getVisits(),
        ]);
    }
    

    #[Route('/users/', name: 'all_users', methods: ['GET', 'POST'])]
    public function allUsers(Request $request, UserRepository $userRep): Response
    {
        return $this->render('dashboard.html.twig', [
            'users' => $userRep->findAll(),
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
