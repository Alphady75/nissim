<?php

namespace App\Controller;

use App\Entity\Financement;
use App\Entity\Projet;
use App\Form\FinancementType;
use App\Repository\FinancementRepository;
use App\Manager\ProjetManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/financement')]
class FinancementController extends AbstractController
{
    #[Route('/financer/{slug}', name: 'app_financement_projet', methods: ['GET', 'POST'])]
    public function financerProjet(
        Request $request, FinancementRepository $financementRepository,
        Projet $projet, ProjetManager $ProjetManager): Response
    {
        $financement = new Financement();
        $form = $this->createForm(FinancementType::class, $financement);
        $form->handleRequest($request);

        $user = $this->getUser();

        // API Stripe
        if($request->getMethod() === "POST"){
            $ressource = $ProjetManager->stripe($_POST, $projet);

            if(null !== $ressource){

                $ProjetManager->inserIntoFinancement($ressource, $projet, $user);

                return $this->render('financement/success.html.twig', [

                ]);
            }
        }

        return $this->renderForm('financement/financer.html.twig', [
            'user'  =>  $user,
            'projet'    => $projet,
            'intentSecret'  =>  $ProjetManager->intentSecret($projet),
            'form' => $form,
        ]);
    }

    #[Route('/payment/projet/{id}', name: 'app_financer', methods: ['POST'])]
    public function financer(Request $request, ProjetManager $ProjetManager, Projet $projet): Response
    {
        $user = $this->getUser();

        // API Stripe
        if($request->getMethod() === "POST"){
            $ressource = $ProjetManager->stripe($_POST, $projet);

            if(null !== $ressource){

                $ProjetManager->inserIntoFinancement($ressource, $projet, $user);

                return $this->render('financement/success.html.twig', [

                ]);
            }
        }
    }

    #[Route('/success', name: 'app_financement_success', methods: ['GET'])]
    public function success(): Response
    {
        return $this->render('financement/success.html.twig', [
            
        ]);
    }
}
