<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'nbVisits' => 50,
            'totalVisits' => 150,
        ]);
    }

    // #[Route('/admin', name: 'admin_dashboard')]
    // public function adminDashboard(): Response
    // {
    //     return $this->render('dashboard/index.html.twig', [
    //         'controller_name' => 'DashboardController',
    //     ]);
    // }
}