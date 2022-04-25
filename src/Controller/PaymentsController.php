<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Financement;
use App\Repository\FinancementRepository;
use App\Manager\ProjetManager;
use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentsController extends AbstractController
{
    private $privateKey;

    public function __construct()
    {
        /**
         * Vérification de l'environnement
         */
        if($_ENV['APP_ENV'] === 'dev'){
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
        }else{
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_LIVE'];
        }
    }

    #[Route('/payer/{projet}/{financement}', name: 'app_payer', methods: ['POST', 'GET'])]
    public function payer(Projet $projet, financement $financement, ProjetRepository $projetRepository, Request $request, ProjetManager $projetManager): Response
    {
        $user = $this->getUser();

        $montant = $financement->getMontant();

        if($montant > 0){

            // Instanciation Stripe
            \Stripe\Stripe::setApiKey($this->privateKey);
            
            $intent = \Stripe\PaymentIntent::create([
                'amount'    =>  $montant * 100,
                'currency'  =>  'eur',
                'payment_method_types'  =>  ['card']
            ]);
            
            // Traitement du formulaire Stripe

            if($request->getMethod() === "POST"){

                if($intent['status'] === "requires_payment_method"){
                    // TODO

                }
            }
            
            //dd($intent);
            
        }else{
            return $this->redirectToRoute('app_projet_proceder_au_financement', ['slug' => $projet->getSlug()]);
        }

        return $this->render('payments/stripe.html.twig', [
            'projet'    => $projet,
            'montant'   => $montant,
            'intentSecret'    =>  $intent['client_secret'],
            'intent'    => $intent,
            'financement' => $financement->getId(),
        ]);
    }

    #[Route('/payments-success/{projet}/{financement}', name: 'app_payment_validate')]
    public function paymentValidate(Financement $financement, Projet $projet, ProjetRepository $projetRepository, FinancementRepository $financementRepository): Response
    {
        $montantCollecte = $projet->getMCollecte() + $financement->getMontant();
        $projet->setMCollecte($montantCollecte);
        $financement->setStatut(1);

        if ($montantCollecte >= $projet->getSmCollecte()) {
            $projet->setFStatut(1);
        }

        $financementRepository->add($financement);
        $projetRepository->add($projet);

        return $this->redirectToRoute('app_financement_success', ['id' => $financement->getId()]);
    }
}
