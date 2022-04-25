<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
#[ORM\HasLifecycleCallbacks]

/**
 * @Vich\Uploadable
 */
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

    /**
     * @Vich\UploadableField(mapping="projets", fileNameProperty="imageName")
     * @var File|null
     */

    //@Assert\Image(maxSize="3M", maxSizeMessage="Image trop volumineuse maximum 3Mb")
    private $imageFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageName;

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

    #[ORM\OneToMany(mappedBy: 'projet', targetEntity: Financement::class)]
    private $financements;

    #[ORM\Column(type: 'boolean')]
    private $visible;

    #[ORM\Column(type: 'float')]
    private $smCollecte;

    #[ORM\Column(type: 'boolean')]
    private $fStatut;

    public function __construct()
    {
        $this->financements = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Financement>
     */
    public function getFinancements(): Collection
    {
        return $this->financements;
    }

    public function addFinancement(Financement $financement): self
    {
        if (!$this->financements->contains($financement)) {
            $this->financements[] = $financement;
            $financement->setProjet($this);
        }

        return $this;
    }

    public function removeFinancement(Financement $financement): self
    {
        if ($this->financements->removeElement($financement)) {
            // set the owning side to null (unless already changed)
            if ($financement->getProjet() === $this) {
                $financement->setProjet(null);
            }
        }

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdated(new \DateTimeImmutable());
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function getSmCollecte(): ?float
    {
        return $this->smCollecte;
    }

    public function setSmCollecte(float $smCollecte): self
    {
        $this->smCollecte = $smCollecte;

        return $this;
    }

    public function getFStatut(): ?bool
    {
        return $this->fStatut;
    }

    public function setFStatut(bool $fStatut): self
    {
        $this->fStatut = $fStatut;

        return $this;
    }
}
