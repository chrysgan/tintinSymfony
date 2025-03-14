<?php

namespace App\Entity;

use App\Repository\ObjetPersoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ObjetPersoRepository::class)]
class ObjetPerso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'objetPersos')]
    private ?objet $objet = null;

    #[ORM\ManyToOne(inversedBy: 'objetPersos')]
    private ?Personnage $relation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getObjet(): ?objet
    {
        return $this->objet;
    }

    public function setObjet(?objet $objet): static
    {
        $this->objet = $objet;

        return $this;
    }

    public function getRelation(): ?Personnage
    {
        return $this->relation;
    }

    public function setRelation(?Personnage $relation): static
    {
        $this->relation = $relation;

        return $this;
    }
}
