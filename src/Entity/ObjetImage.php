<?php

namespace App\Entity;

use App\Repository\ObjetImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ObjetImageRepository::class)]
class ObjetImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column]
    private ?int $ordre = null;

    #[ORM\ManyToOne(inversedBy: 'objetImages')]
    #[ORM\JoinColumn(name: 'idobjet', referencedColumnName: 'id', nullable:'false')]
    private ?Objet $idobjet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): static
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getIdobjet(): ?Objet
    {
        return $this->idobjet;
    }

    public function setIdobjet(?Objet $idobjet): static
    {
        $this->idobjet = $idobjet;

        return $this;
    }
}
