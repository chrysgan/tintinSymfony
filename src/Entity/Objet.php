<?php

namespace App\Entity;

use App\Repository\ObjetRepository;
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

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $taille = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $reference = null;

    // TODO : ajouter une valeur par defaut -1.00
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $prix = null;

    #[ORM\Column(nullable: true)]
    private ?int $annee = null;

    #[ORM\Column(nullable: true)]
    private ?int $mois = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $poids = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $rangement = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantAchat = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estPossede = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptionExemplaire = null;

    #[ORM\Column]
    private ?bool $actif = null;

    #[ORM\ManyToOne(inversedBy: 'objets')]
    #[ORM\JoinColumn(name: 'idserie', referencedColumnName: 'idserie', nullable: true)]
    private ?Serie $idserie = null;

    #[ORM\ManyToOne(inversedBy: 'objets')]
    #[ORM\JoinColumn(name: 'idediteur', referencedColumnName: 'idediteur', nullable: false)]
    private ?Editeur $idediteur = null;

    #[ORM\ManyToOne(inversedBy: 'objets')]
    #[ORM\JoinColumn(name: 'idcategorie', referencedColumnName: 'idcategorie', nullable: false)]
    private ?Categorie $idcategorie = null;

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

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(?string $taille): static
    {
        $this->taille = $taille;

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

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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

    public function getRangement(): ?string
    {
        return $this->rangement;
    }

    public function setRangement(?string $rangement): static
    {
        $this->rangement = $rangement;

        return $this;
    }

    public function getMontantAchat(): ?float
    {
        return $this->montantAchat;
    }

    public function setMontantAchat(?float $montantAchat): static
    {
        $this->montantAchat = $montantAchat;

        return $this;
    }

    public function isEstPossede(): ?bool
    {
        return $this->estPossede;
    }

    public function setEstPossede(?bool $estPossede): static
    {
        $this->estPossede = $estPossede;

        return $this;
    }

    public function getDescriptionExemplaire(): ?string
    {
        return $this->descriptionExemplaire;
    }

    public function setDescriptionExemplaire(?string $descriptionExemplaire): static
    {
        $this->descriptionExemplaire = $descriptionExemplaire;

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

    public function getIdserie(): ?Serie
    {
        return $this->idserie;
    }

    public function setIdserie(?Serie $idserie): static
    {
        $this->idserie = $idserie;

        return $this;
    }

    public function getIdediteur(): ?Editeur
    {
        return $this->idediteur;
    }

    public function setIdediteur(?Editeur $idediteur): static
    {
        $this->idediteur = $idediteur;

        return $this;
    }

    public function getIdcategorie(): ?Categorie
    {
        return $this->idcategorie;
    }

    public function setIdcategorie(?Categorie $idcategorie): static
    {
        $this->idcategorie = $idcategorie;

        return $this;
    }
}
