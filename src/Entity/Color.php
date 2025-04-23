<?php

namespace App\Entity;

use App\Repository\ColorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColorRepository::class)]
class Color
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $color = null;

    #[ORM\Column(length: 255)]
    private ?string $hexa = null;

    /**
     * @var Collection<int, bead>
     */
    #[ORM\ManyToMany(targetEntity: Bead::class, inversedBy: 'colors')]
    private Collection $bead;

    public function __construct()
    {
        $this->bead = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getHexa(): ?string
    {
        return $this->hexa;
    }

    public function setHexa(string $hexa): static
    {
        $this->hexa = $hexa;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->color;
    }


    /**
     * @return Collection<int, bead>
     */
    public function getBead(): Collection
    {
        return $this->bead;
    }

    public function addBead(Bead $bead): static
    {
        if (!$this->bead->contains($bead)) {
            $this->bead->add($bead);
        }

        return $this;
    }

    public function removeBead(Bead $bead): static
    {
        $this->bead->removeElement($bead);

        return $this;
    }
}
