<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Repository\ProjetRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/projets')]
class ProjetsController extends AbstractController
{
    #[Route('/', name: 'app_projets', methods: ['GET'])]
    public function index(ProjetRepository $projetRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $projets = $paginator->paginate(
            $projetRepository->findByDateDesc(),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('projets/index.html.twig', [
            'projets' => $projets
        ]);
    }

    #[Route('/{slug}', name: 'app_projet_detail', methods: ['GET'])]
    public function details(Projet $projet): Response
    {   
        return $this->render('projets/detail.html.twig', [
            'projet' => $projet,
        ]);
    }
}
