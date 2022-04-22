<?php

namespace App\Entity\Traits;
use Doctrine\ORM\Mapping as ORM;

class StripeFields
{
    #[ORM\Column(type: 'string', length: 255)]
    private $reference;

    #[ORM\Column(type: 'string', length: 255)]
    private $stripeToken;

    #[ORM\Column(type: 'string', length: 255)]
    private $brandStripe;

    #[ORM\Column(type: 'string', length: 255)]
    private $last4Stripe;

    #[ORM\Column(type: 'string', length: 255)]
    private $idChargeStripe;

    #[ORM\Column(type: 'string', length: 255)]
    private $statusStripe;

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
