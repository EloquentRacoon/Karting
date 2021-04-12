<?php

namespace App\Entity;

use App\Repository\ActiviteitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActiviteitRepository::class)
 */
class Activiteit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="datum", type="datetime")
     * @Assert\NotBlank(message="vul een datum in")
     */
    private $datum;

    /**
     * @ORM\Column(name="tijd",type="datetime")
     * @Assert\NotBlank(message="vul een tijd in")
     */
    private $tijd;

    /**
     * @ORM\ManyToOne(targetEntity=Soortactiviteit::class, inversedBy="activiteit")
     * @ORM\JoinColumn(nullable=false)
     */
    private $soortactiviteit;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="activiteiten")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getDatum(): \DateTimeInterface
    {
        return $this->datum;
    }

    public function setDatum(\DateTimeInterface $datum): self
    {
        $this->datum = $datum;

        return $this;
    }

    public function getTijd(): \DateTimeInterface
    {
        return $this->tijd;
    }

    public function setTijd(\DateTimeInterface $tijd): self
    {
        $this->tijd = $tijd;

        return $this;
    }

    public function getSoortactiviteit(): Soortactiviteit
    {
        return $this->soortactiviteit;
    }

    public function setSoortactiviteit(Soortactiviteit $soortactiviteit): self
    {
        $this->soortactiviteit = $soortactiviteit;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

}
