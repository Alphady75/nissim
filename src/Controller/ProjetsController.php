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

    #[Route('/new', name: 'app_projet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProjetRepository $projetRepository): Response
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Auteur du projet
            $projet->setUser($this->getUser());

            $projetRepository->add($projet);
            return $this->redirectToRoute('app_projets', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('projets/new.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_projet_detail', methods: ['GET'])]
    public function details(Projet $projet): Response
    {   
        return $this->render('projets/detail.html.twig', [
            'projet' => $projet,
        ]);
    }

    /*#[Route('/{id}/edit', name: 'app_projet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Projet $projet, ProjetRepository $projetRepository): Response
    {
        // Seul l'auteur du projet poura le modifier
        $this->denyAccessUnlessGranted('projet_edit', $projet);

        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projetRepository->add($projet);
            return $this->redirectToRoute('app_projets', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('projets/edit.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }*/

    #[Route('/{id}', name: 'app_projet_delete', methods: ['POST'])]
    public function delete(Request $request, Projet $projet, ProjetRepository $projetRepository): Response
    {
        // Seul l'auteur du projet poura le supprimer
        $this->denyAccessUnlessGranted('app_projet_edit', $projet);

        if ($this->isCsrfTokenValid('delete'.$projet->getId(), $request->request->get('_token'))) {
            $projetRepository->remove($projet);
        }

        return $this->redirectToRoute('app_projet', [], Response::HTTP_SEE_OTHER);
    }
}
