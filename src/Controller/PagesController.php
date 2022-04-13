<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/pages', name: 'app_pages')]
    public function index(): Response
    {
        return $this->render('pages/index.html.twig', [
            'controller_name' => 'PagesController',
        ]);
    }

    #[Route('/historique', name: 'app_pages_historique')]
    public function historique(): Response
    {
        return $this->render('pages/historique.html.twig', [
            'controller_name' => 'PagesController',
        ]);
    }

    #[Route('/a-propos', name: 'app_pages_apropos')]
    public function aPropos(): Response
    {
        return $this->render('pages/apropos.html.twig', [
            'controller_name' => 'PagesController',
        ]);
    }

    #[Route('/contact', name: 'app_pages_contact')]
    public function contact(): Response
    {
        return $this->render('pages/contact.html.twig', [
            'controller_name' => 'PagesController',
        ]);
    }
}
