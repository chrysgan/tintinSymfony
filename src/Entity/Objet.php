<?php

namespace App\Entity;

use App\Repository\ObjetRepository;
use Doctrine\DBAL\Schema\DefaultExpression\CurrentTimestamp;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ObjetRepository::class)]
class Objet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'objets')]
    private ?Serie $serie = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2, nullable: true)]
    private ?string $taille = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2, nullable: true, options: ['default' => '-1.00'])]
    private ?string $prix = null;

    #[ORM\ManyToOne(inversedBy: 'objets')]
    private ?Editeur $editeur = null;

    #[ORM\ManyToOne(inversedBy: 'objets')]
    private ?Categorie $categorie = null;

    #[ORM\Column(nullable: true)]
    private ?int $annee = null;

    #[ORM\Column(nullable: true)]
    private ?int $mois = null;

    #[ORM\Column(options: ['default' => new CurrentTimestamp()])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $poids = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $rangement = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estPossede = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2, nullable: true)]
    private ?string $montantAchat = null;

    #[ORM\Column(nullable: true,options: ['default' => '0'])]
    private ?bool $actif = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptionPossession = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getSerie(): ?Serie
    {
        return $this->serie;
    }

    public function setSerie(?Serie $serie): static
    {
        $this->serie = $serie;

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

    public function setPrix(?string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getEditeur(): ?Editeur
    {
        return $this->editeur;
    }

    public function setEditeur(?Editeur $editeur): static
    {
        $this->editeur = $editeur;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

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

    public function isEstPossede(): ?bool
    {
        return $this->estPossede;
    }

    public function setEstPossede(?bool $estPossede): static
    {
        $this->estPossede = $estPossede;

        return $this;
    }

    public function getMontantAchat(): ?string
    {
        return $this->montantAchat;
    }

    public function setMontantAchat(?string $montantAchat): static
    {
        $this->montantAchat = $montantAchat;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(?bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }

    public function getDescriptionPossession(): ?string
    {
        return $this->descriptionPossession;
    }

    public function setDescriptionPossession(?string $descriptionPossession): static
    {
        $this->descriptionPossession = $descriptionPossession;

        return $this;
    }
}
