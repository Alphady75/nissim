<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAt;
//use App\Entity\Traits\StripeFields;
use App\Repository\FinancementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FinancementRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Financement
{
    use CreatedAt;
    //use StripeFields;

    const DEVISE = 'eur';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'financements')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'float')]
    private $montant;

    #[ORM\ManyToOne(targetEntity: Projet::class, inversedBy: 'financements')]
    private $projet;

    #[ORM\Column(type: 'boolean')]
    private $statut;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $reference;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $stripeToken;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $brandStripe;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $last4Stripe;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $idChargeStripe;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $statusStripe;

    public function __construct()
    {
        $this->project = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): self
    {
        $this->projet = $projet;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getStripeToken(): ?string
    {
        return $this->stripeToken;
    }

    public function setStripeToken(string $stripeToken): self
    {
        $this->stripeToken = $stripeToken;

        return $this;
    }

    public function getBrandStripe(): ?string
    {
        return $this->brandStripe;
    }

    public function setBrandStripe(string $brandStripe): self
    {
        $this->brandStripe = $brandStripe;

        return $this;
    }

    public function getLast4Stripe(): ?string
    {
        return $this->last4Stripe;
    }

    public function setLast4Stripe(string $last4Stripe): self
    {
        $this->last4Stripe = $last4Stripe;

        return $this;
    }

    public function getIdChargeStripe(): ?string
    {
        return $this->idChargeStripe;
    }

    public function setIdChargeStripe(string $idChargeStripe): self
    {
        $this->idChargeStripe = $idChargeStripe;

        return $this;
    }

    public function getStatusStripe(): ?string
    {
        return $this->statusStripe;
    }

    public function setStatusStripe(string $statusStripe): self
    {
        $this->statusStripe = $statusStripe;

        return $this;
    }
}
