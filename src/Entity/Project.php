<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Bead>
     */
    #[ORM\ManyToMany(targetEntity: Bead::class, inversedBy: 'projects')]
    private Collection $bead;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'projects')]
    private Collection $category;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $user = null;


    private array $newCategories = [];


    public function __construct()
    {
        $this->bead = new ArrayCollection();
        $this->category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    /**
     * @return Collection<int, Bead>
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

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->category->removeElement($category);

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

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getNewCategories(): array
    {
        return $this->newCategories;
    }

    public function setNewCategories(array $newCategories): void
    {
        $this->newCategories = $newCategories;
    }
}
