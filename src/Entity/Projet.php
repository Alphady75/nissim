<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\ProjetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Projet
{
    use Timestamp;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'projets')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'text')]
    private $fDescriptive;

    #[ORM\Column(type: 'text')]
    private $aFiabilite;

    #[ORM\Column(type: 'text')]
    private $dInfReglementaire;

    #[ORM\Column(type: 'datetime')]
    private $endDate;

    #[ORM\Column(type: 'float', nullable: true)]
    private $mCollecte;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
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

    public function getFDescriptive(): ?string
    {
        return $this->fDescriptive;
    }

    public function setFDescriptive(string $fDescriptive): self
    {
        $this->fDescriptive = $fDescriptive;

        return $this;
    }

    public function getAFiabilite(): ?string
    {
        return $this->aFiabilite;
    }

    public function setAFiabilite(string $aFiabilite): self
    {
        $this->aFiabilite = $aFiabilite;

        return $this;
    }

    public function getDInfReglementaire(): ?string
    {
        return $this->dInfReglementaire;
    }

    public function setDInfReglementaire(string $dInfReglementaire): self
    {
        $this->dInfReglementaire = $dInfReglementaire;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getMCollecte(): ?int
    {
        return $this->mCollecte;
    }

    public function setMCollecte(?int $mCollecte): self
    {
        $this->mCollecte = $mCollecte;

        return $this;
    }
}
