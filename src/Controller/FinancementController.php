<?php

namespace App\Controller;

use App\Entity\Financement;
use App\Entity\Projet;
use App\Form\FinancementType;
use App\Repository\FinancementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/financement')]
class FinancementController extends AbstractController
{
    #[Route('/financement-projet/{slug}', name: 'app_financement_projet', methods: ['GET', 'POST'])]
    public function financerProjet(Request $request, FinancementRepository $financementRepository, Projet $projet): Response
    {
        $financement = new Financement();
        $form = $this->createForm(FinancementType::class, $financement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $financement->setProjet($projet);
            
            $projet->setMCollecte($projet->getMCollecte() + $financement->getMontant());

            $financement->setUser($this->getUser());

            $financementRepository->add($financement);
            return $this->redirectToRoute('app_financement_success', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('financement/financement.html.twig', [
            'projet'    => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/success', name: 'app_financement_success', methods: ['GET'])]
    public function success(): Response
    {
        return $this->render('financement/success.html.twig', [
            
        ]);
    }
}
