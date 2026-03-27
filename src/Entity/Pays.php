<?php

namespace App\Entity;

use App\Repository\PaysRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaysRepository::class)]
class Pays
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $code = null;

    #[ORM\Column(length: 2)]
    private ?string $alpha2 = null;

    #[ORM\Column(length: 3)]
    private ?string $alpha3 = null;

    #[ORM\Column(length: 45)]
    private ?string $nomFr = null;

    #[ORM\Column(length: 45)]
    private ?string $nomEn = null;

    /**
     * @var Collection<int, Editeur>
     */
    #[ORM\OneToMany(targetEntity: Editeur::class, mappedBy: 'pays')]
    private Collection $editeurs;

    public function __construct()
    {
        $this->editeurs = new ArrayCollection();
    }

    public function __toString():string
    {
        return $this->getNomFr() ?? '';
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

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getAlpha2(): ?string
    {
        return $this->alpha2;
    }

    public function setAlpha2(string $alpha2): static
    {
        $this->alpha2 = $alpha2;

        return $this;
    }

    public function getAlpha3(): ?string
    {
        return $this->alpha3;
    }

    public function setAlpha3(string $alpha3): static
    {
        $this->alpha3 = $alpha3;

        return $this;
    }

    public function getNomFr(): ?string
    {
        return $this->nomFr;
    }

    public function setNomFr(string $nomFr): static
    {
        $this->nomFr = $nomFr;

        return $this;
    }

    public function getNomEn(): ?string
    {
        return $this->nomEn;
    }

    public function setNomEn(string $nomEn): static
    {
        $this->nomEn = $nomEn;

        return $this;
    }

    /**
     * @return Collection<int, Editeur>
     */
    public function getEditeurs(): Collection
    {
        return $this->editeurs;
    }

    public function addEditeur(Editeur $editeur): static
    {
        if (!$this->editeurs->contains($editeur)) {
            $this->editeurs->add($editeur);
            $editeur->setPays($this);
        }

        return $this;
    }

    public function removeEditeur(Editeur $editeur): static
    {
        if ($this->editeurs->removeElement($editeur)) {
            // set the owning side to null (unless already changed)
            if ($editeur->getPays() === $this) {
                $editeur->setPays(null);
            }
        }

        return $this;
    }
}
