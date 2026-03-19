<?php

namespace App\Entity;

use App\Repository\EditeurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EditeurRepository::class)]
class Editeur
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idediteur = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    private ?int $anneeCreation = null;

    #[ORM\Column(nullable: true)]
    private ?int $anneeFermeture = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'editeurs')]
    #[ORM\JoinColumn(name: 'idpays', referencedColumnName: 'idpays', nullable: false)]
    private ?Pays $idpays = null;

    /**
     * @var Collection<int, Objet>
     */
    #[ORM\OneToMany(targetEntity: Objet::class, mappedBy: 'idediteur')]
    private Collection $objets;

    public function __construct()
    {
        $this->objets = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getNom();
    }
    public function getIdediteur(): ?int
    {
        return $this->idediteur;
    }

    public function setIdediteur(int $idediteur): static
    {
        $this->idediteur = $idediteur;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getAnneeCreation(): ?int
    {
        return $this->anneeCreation;
    }

    public function setAnneeCreation(?int $anneeCreation): static
    {
        $this->anneeCreation = $anneeCreation;

        return $this;
    }

    public function getAnneeFermeture(): ?int
    {
        return $this->anneeFermeture;
    }

    public function setAnneeFermeture(?int $anneeFermeture): static
    {
        $this->anneeFermeture = $anneeFermeture;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getIdpays(): ?Pays
    {
        return $this->idpays;
    }

    public function setIdpays(?Pays $idpays): static
    {
        $this->idpays = $idpays;

        return $this;
    }

    /**
     * @return Collection<int, Objet>
     */
    public function getObjets(): Collection
    {
        return $this->objets;
    }

    public function addObjet(Objet $objet): static
    {
        if (!$this->objets->contains($objet)) {
            $this->objets->add($objet);
            $objet->setIdediteur($this);
        }

        return $this;
    }

    public function removeObjet(Objet $objet): static
    {
        if ($this->objets->removeElement($objet)) {
            // set the owning side to null (unless already changed)
            if ($objet->getIdediteur() === $this) {
                $objet->setIdediteur(null);
            }
        }

        return $this;
    }
}
