<?php

namespace App\Entity;

use App\Repository\SoortactiviteitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @ORM\Entity(repositoryClass=SoortactiviteitRepository::class)
 */
class Soortactiviteit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $naam;

    /**
     * @ORM\Column(type="integer")
     */
    private $minLeeftijd;

    /**
     * @ORM\Column(type="time")
     */
    private $tijdsduur;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $prijs;

    /**
     * @ORM\OneToMany(targetEntity=Activiteit::class, mappedBy="soortactiviteit")
     */
    private $activiteit;

    public function __construct()
    {
        $this->activiteit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNaam(): ?string
    {
        return $this->naam;
    }

    public function setNaam(string $naam): self
    {
        $this->naam = $naam;

        return $this;
    }

    public function getMinLeeftijd(): ?int
    {
        return $this->minLeeftijd;
    }

    public function setMinLeeftijd(int $minLeeftijd): self
    {
        $this->minLeeftijd = $minLeeftijd;

        return $this;
    }

    public function getTijdsduur(): ?\DateTimeInterface
    {
        return $this->tijdsduur;
    }

    public function setTijdsduur(?\DateTimeInterface $tijdsduur): self
    {
        $this->tijdsduur = $tijdsduur;

        return $this;
    }

    public function getPrijs(): ?int
    {
        return $this->prijs;
    }

    public function setPrijs(int $prijs): self
    {
        $this->prijs = $prijs;

        return $this;
    }

    /**
     * @return Collection|Activiteit[]
     */
    public function getActiviteit(): Collection
    {
        return $this->activiteit;
    }

    public function addActiviteit(Activiteit $activiteit): self
    {
        if (!$this->activiteit->contains($activiteit)) {
            $this->activiteit[] = $activiteit;
            $activiteit->setSoortactiviteit($this);
        }

        return $this;
    }

    public function removeActiviteit(Activiteit $activiteit): self
    {
        if ($this->activiteit->removeElement($activiteit)) {
            // set the owning side to null (unless already changed)
            if ($activiteit->getSoortactiviteit() === $this) {
                $activiteit->setSoortactiviteit(null);
            }
        }

        return $this;
    }
}
