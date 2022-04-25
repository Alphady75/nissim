<?php

namespace App\Manager;

use App\Entity\Financement;
use App\Entity\Projet;
use App\Entity\User;
use App\Services\StripeService;
use Doctrine\ORM\EntityManagerInterface;

class ProjetManager {

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var StripeService
     */
    protected $stripeService;

    /**
     * @param EntityManagerInterface $entitymanager
     * @param StripeService $stripeService
     */
    public function __construct(EntityManagerInterface $entitymanager, StripeService $stripeService)
    {
        $this->em = $entitymanager;
        $this->stripeService = $stripeService;
    }

    public function getProjets()
    {
        return $this->em->getRepository(Projet::class)->findAll();
    }

    public function intentSecret(Projet $projet)
    {
        $intent = $this->stripeService->paymentIntent($projet);

        return $intent['client_secret'] ?? null;
    }

        /**
     * Permet de créer un financement
     * @param array $stripeParameter
     * @param Projet $projet
     */
    public function stripe(array $stripeParameter, Projet $projet)
    {
        $ressource = null;
        $data = $this->stripeService->stripe($stripeParameter, $projet);

        if($data){ dd('true');
            $ressource = [
                'stripeBrand'   => $data['charges']['data'][0]['payment_method_details']['card']['brand'],
                'stripeLast4'   => $data['charges']['data'][0]['payment_method_details']['card']['last4'],
                'stripeId'      => $data['charges']['data'][0]['id'],
                'stripeStatus'  => $data['charges']['data'][0]['status'],
                'stripeToken'   => $data['client_secret'],
            ];
        }

        return $ressource;
    }

    /**
     * Permet de créer un financement (enregistrer un financement dans la BD)
     * @param array $ressource
     * @param Projet $projet
     * @param User $user
     */
    public function inserIntoFinancement(array $ressource, Projet $projet, User $user)
    {
       $financement = new Financement;
       $financement->setUser($user);
       $financement->setProjet($projet);
       $financement->setMontant($projet->getMCollecte());  // Modifiable
       $financement->setReference(uniqid('', false));
       $financement->setBrandStripe($ressource['stripeBrand']);
       $financement->setLast4Stripe($ressource['stripeLast4']);
       $financement->setIdChargeStripe($ressource['stripeId']);
       $financement->setStatusStripe($ressource['stripeStatus']);
       $financement->setStripeToken($ressource['stripeToken']);

       $this->em->persist($financement);
       $this->em->flush();
    }
}