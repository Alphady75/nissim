<?php

namespace App\Controller;

use App\Entity\Financement;
use App\Entity\Projet;
use App\Form\FinancementType;
use App\Repository\FinancementRepository;
use App\Manager\ProjetManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/financement')]
class FinancementController extends AbstractController
{
    #[Route('/projets/{slug}', name: 'app_projet_proceder_au_financement', methods: ['GET', 'POST'])]
    public function financerProjet(
        Request $request, FinancementRepository $financementRepository,
        Projet $projet, ProjetManager $ProjetManager): Response
    {
        $financement = new Financement();
        $form = $this->createForm(FinancementType::class, $financement, []);

        $form->handleRequest($request);

        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $financement->setStatut(0);
            $financement->setUser($user);
            $financement->setProjet($projet);
            $financement->setMontant($financement->getMontant());
            $financementRepository->add($financement);

            $financement = $financementRepository->find($financement);

            $form = $this->createForm(FinancementType::class, $financement, [
                'action'    =>  $this->generateUrl('app_payer', [
                    'projet' => $projet->getId(),
                    'financement'  => $financement->getId(),
                ]),
                'method' =>  'POST',
            ]);

            return $this->redirectToRoute('app_payer', ['financement' => $financement->getId(), 'projet'=> $projet->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('financement/procedToPayment.html.twig', [
            'user'  =>  $user,
            'projet'    => $projet,
            //'intentSecret'  =>  $ProjetManager->intentSecret($projet),
            'form' => $form,
        ]);
    }

    #[Route('/success/{id}', name: 'app_financement_success', methods: ['GET'])]
    public function success(Financement $financement): Response
    {
        return $this->render('financement/success.html.twig', [
            'financement' => $financement,
        ]);
    }
}
