<?php

namespace App\Entity;

use App\Repository\ObjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ObjetRepository::class)]
class Objet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(nullable: true)]
    private ?float $taille = null;

    #[ORM\Column(nullable: true)]
    private ?int $poids = null;

    #[ORM\Column(nullable: true)]
    private ?int $annee = null;

    #[ORM\Column(nullable: true)]
    private ?int $mois = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix = null;

    #[ORM\Column]
    private ?bool $possede = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lieu_rangement = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $detail_possession = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix_possede = null;

    #[ORM\Column]
    private ?bool $actif = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_modification = null;

    #[ORM\ManyToOne(inversedBy: 'objets')]
    private ?editeur $editeur = null;

    #[ORM\ManyToOne(inversedBy: 'objets')]
    private ?serie $serie = null;

    #[ORM\ManyToOne(inversedBy: 'objets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?typeObjet $type = null;

    /**
     * @var Collection<int, ObjetImage>
     */
    #[ORM\OneToMany(targetEntity: ObjetImage::class, mappedBy: 'objet', orphanRemoval: true)]
    private Collection $objetImages;

    /**
     * @var Collection<int, ObjetPerso>
     */
    #[ORM\OneToMany(targetEntity: ObjetPerso::class, mappedBy: 'objet')]
    private Collection $objetPersos;

    public function __construct()
    {
        $this->objetImages = new ArrayCollection();
        $this->objetPersos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getTaille(): ?float
    {
        return $this->taille;
    }

    public function setTaille(?float $taille): static
    {
        $this->taille = $taille;

        return $this;
    }

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(?int $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(?int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function getMois(): ?int
    {
        return $this->mois;
    }

    public function setMois(?int $mois): static
    {
        $this->mois = $mois;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function isPossede(): ?bool
    {
        return $this->possede;
    }

    public function setPossede(bool $possede): static
    {
        $this->possede = $possede;

        return $this;
    }

    public function getLieuRangement(): ?string
    {
        return $this->lieu_rangement;
    }

    public function setLieuRangement(?string $lieu_rangement): static
    {
        $this->lieu_rangement = $lieu_rangement;

        return $this;
    }

    public function getDetailPossession(): ?string
    {
        return $this->detail_possession;
    }

    public function setDetailPossession(?string $detail_possession): static
    {
        $this->detail_possession = $detail_possession;

        return $this;
    }

    public function getPrixPossede(): ?float
    {
        return $this->prix_possede;
    }

    public function setPrixPossede(?float $prix_possede): static
    {
        $this->prix_possede = $prix_possede;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): static
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->date_modification;
    }

    public function setDateModification(\DateTimeInterface $date_modification): static
    {
        $this->date_modification = $date_modification;

        return $this;
    }

    public function getEditeur(): ?editeur
    {
        return $this->editeur;
    }

    public function setEditeur(?editeur $editeur): static
    {
        $this->editeur = $editeur;

        return $this;
    }

    public function getSerie(): ?serie
    {
        return $this->serie;
    }

    public function setSerie(?serie $serie): static
    {
        $this->serie = $serie;

        return $this;
    }

    public function getType(): ?typeObjet
    {
        return $this->type;
    }

    public function setType(?typeObjet $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, ObjetImage>
     */
    public function getObjetImages(): Collection
    {
        return $this->objetImages;
    }

    public function addObjetImage(ObjetImage $objetImage): static
    {
        if (!$this->objetImages->contains($objetImage)) {
            $this->objetImages->add($objetImage);
            $objetImage->setObjet($this);
        }

        return $this;
    }

    public function removeObjetImage(ObjetImage $objetImage): static
    {
        if ($this->objetImages->removeElement($objetImage)) {
            // set the owning side to null (unless already changed)
            if ($objetImage->getObjet() === $this) {
                $objetImage->setObjet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ObjetPerso>
     */
    public function getObjetPersos(): Collection
    {
        return $this->objetPersos;
    }

    public function addObjetPerso(ObjetPerso $objetPerso): static
    {
        if (!$this->objetPersos->contains($objetPerso)) {
            $this->objetPersos->add($objetPerso);
            $objetPerso->setObjet($this);
        }

        return $this;
    }

    public function removeObjetPerso(ObjetPerso $objetPerso): static
    {
        if ($this->objetPersos->removeElement($objetPerso)) {
            // set the owning side to null (unless already changed)
            if ($objetPerso->getObjet() === $this) {
                $objetPerso->setObjet(null);
            }
        }

        return $this;
    }
}
