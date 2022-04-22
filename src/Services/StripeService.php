<?php

namespace App\Services;

use App\Entity\Financement;
use App\Entity\Projet;

class StripeService {

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
    
    /**
     * Recupère le projet à financer et renvoi un formulaire
     * pour effectuer un paiement
     * 
     * @param Projet $projet
     * @return \Stripe\PaymentIntent
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function paymentIntent(Projet $projet)
    {
        // On set (appel) la clé à utiliser: privée recommandée côté server
        \Stripe\Stripe::setApiKey($this->privateKey);

        return \Stripe\PaymentIntent::create([
            'amount'    =>  $projet->getMCollecte() * 100, // Le montant à financer
            'currency'  =>  Financement::DEVISE, // La dévise
            'payment_method_types'  =>  ['card']

        ]);
    }

    public function payment($montant, $devise, $description, array $stripeParameter)
    {
        // On set (appel) la clé à utiliser: privée recommandée côté server
        \Stripe\Stripe::setApiKey($this->privateKey);
        $payment_intent = null;

        if(isset($stripeParameter['stripeIntentId'])){
            $payment_intent = \Stripe\PaymentIntent::retrieve($stripeParameter['stripeIntentId']);
        }

        if($stripeParameter['stripeIntentId'] === 'succeeced'){
            //TODO
        }else{
            $payment_intent->cancel();
        }

        return $payment_intent;

    }

    /**
     * @param array $stripeParameter
     * @param Projet $projet
     * @return \Stripe\PaymentIntent|null
     */
    public function stripe(array $stripeParameter, Projet $projet)
    {
        return $this->payment(
            $projet->getMCollecte() * 100,
            Financement::DEVISE,
            $projet->getName(),
            $stripeParameter
        );
    }
}