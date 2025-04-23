<?php

namespace App\Entity;

use App\Repository\BeadsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: BeadsRepository::class)]
class Bead
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $number = null;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $stock = null;

    #[ORM\Column(nullable: true)]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    private ?int $userid = null;

    #[ORM\Column]
    #[Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Color>
     */
    #[ORM\ManyToMany(targetEntity: Color::class, mappedBy: 'bead')]
    private Collection $colors;

    // Mixed bead components (self-referencing ManyToMany)
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'usedInMixes')]
    #[ORM\JoinTable(name: 'bead_mix')]
    private Collection $components;

    // Beads that use this one in their mix
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'components')]

    private Collection $usedInMixes;

    public function __construct()
    {
        $this->colors = new ArrayCollection();
        $this->components = new ArrayCollection();
        $this->usedInMixes = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getUserid(): ?int
    {
        return $this->userid;
    }

    public function setUserid(?int $userid): static
    {
        $this->userid = $userid;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
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

    /**
     * @return Collection<int, Color>
     */
    public function getColors(): Collection
    {
        return $this->colors;
    }

    public function addColor(Color $color): static
    {
        if (!$this->colors->contains($color)) {
            $this->colors->add($color);
            $color->addBead($this);
        }

        return $this;
    }

    public function removeColor(Color $color): static
    {
        if ($this->colors->removeElement($color)) {
            $color->removeBead($this);
        }

        return $this;
    }
    /** @return Collection<int, Bead> */
    public function getComponents(): Collection
    {
        return $this->components;
    }

    public function addComponent(self $bead): self
    {
        // dd("in addcomponents");
        if (!$this->components->contains($bead)) {
            // dd($this->components);
            $this->components->add($bead);
            $bead->usedInMixes[] = $this;
            // dd($bead->usedInMixes);
        }

        return $this;
    }

    public function setComponents(iterable $components): self
    {
        // dd("in setcomponents");

        $this->components = new ArrayCollection();
        foreach ($components as $component) {
            $this->addComponent($component);
        }
        return $this;
    }

    public function removeComponent(self $bead): self
    {
        // if ($this->components->removeElement($bead)) {
        $this->components->removeElement($bead);
        $bead->usedInMixes->removeElement($this);
        // }
        return $this;
    }

    /** @return Collection<int, Bead> */
    public function getUsedInMixes(): Collection
    {
        return $this->usedInMixes;
    }
}
