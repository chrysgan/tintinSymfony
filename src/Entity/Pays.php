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
    #[ORM\Column]
    private ?int $idpays = null;

    #[ORM\Column]
    private ?int $code = null;

    #[ORM\Column(length: 2)]
    private ?string $alpha2 = null;

    #[ORM\Column(length: 3)]
    private ?string $alpha3 = null;

    #[ORM\Column(length: 45)]
    private ?string $nomfr = null;

    #[ORM\Column(length: 45)]
    private ?string $nomen = null;

    /**
     * @var Collection<int, Editeur>
     */
    #[ORM\OneToMany(targetEntity: Editeur::class, mappedBy: 'idpays')]
    private Collection $editeurs;

    public function __construct()
    {
        $this->editeurs = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getNomfr();
    }
    public function getIdpays(): ?int
    {
        return $this->idpays;
    }

    public function setIdpays(int $idpays): static
    {
        $this->idpays = $idpays;

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

    public function getNomfr(): ?string
    {
        return $this->nomfr;
    }

    public function setNomfr(string $nomfr): static
    {
        $this->nomfr = $nomfr;

        return $this;
    }

    public function getNomen(): ?string
    {
        return $this->nomen;
    }

    public function setNomen(string $nomen): static
    {
        $this->nomen = $nomen;

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
            $editeur->setIdpays($this);
        }

        return $this;
    }

    public function removeEditeur(Editeur $editeur): static
    {
        if ($this->editeurs->removeElement($editeur)) {
            // set the owning side to null (unless already changed)
            if ($editeur->getIdpays() === $this) {
                $editeur->setIdpays(null);
            }
        }

        return $this;
    }
}
