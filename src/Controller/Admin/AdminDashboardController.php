<?php

namespace App\Controller\Admin;

use App\Repository\FinancementRepository;
use App\Repository\ProjetRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(UserRepository $userRepository, ProjetRepository $projetRepository, FinancementRepository $financementRepository): Response
    {
        return $this->render('admin/admin_dashboard/index.html.twig', [
            'users' =>  $userRepository->findLastedRegistration(),
            'financements' =>  $financementRepository->findLasted(6),
            'projets' => $projetRepository->findAll(),

            'controller_name' => 'AdminDashboardController',
        ]);
    }
}
