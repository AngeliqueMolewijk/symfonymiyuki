<?php

namespace App\Entity;

use App\Repository\BeadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;
use Gedmo\Mapping\Annotation as Gedmo;

#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false)]
#[ORM\Entity(repositoryClass: BeadRepository::class)]
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
    private ?string $image = null;


    #[ORM\Column(nullable: true)]
    #[Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;


    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $deletedAt = null;


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

    /**
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'bead')]
    private Collection $projects;

    /**
     * @var Collection<int, UserBead>
     */
    #[ORM\OneToMany(targetEntity: UserBead::class, mappedBy: 'bead')]
    private Collection $userBeads;


    public function __construct()
    {
        $this->colors = new ArrayCollection();
        $this->components = new ArrayCollection();
        $this->usedInMixes = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->userBeads = new ArrayCollection();
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


    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

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
        if (!$this->components->contains($bead)) {
            $this->components->add($bead);
            $bead->usedInMixes[] = $this;
        }

        return $this;
    }

    public function removeComponent(self $bead): self
    {
        $this->components->removeElement($bead);
        $bead->usedInMixes->removeElement($this);
        return $this;
    }

    /** @return Collection<int, Bead> */
    public function getUsedInMixes(): Collection
    {
        return $this->usedInMixes;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addBead($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removeBead($this);
        }

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    /**
     * @return Collection<int, UserBead>
     */
    public function getUserBeads(): Collection
    {
        return $this->userBeads;
    }

    public function addUserBead(UserBead $userBead): static
    {
        if (!$this->userBeads->contains($userBead)) {
            $this->userBeads->add($userBead);
            $userBead->setBead($this);
        }

        return $this;
    }

    public function removeUserBead(UserBead $userBead): static
    {
        if ($this->userBeads->removeElement($userBead)) {
            // set the owning side to null (unless already changed)
            if ($userBead->getBead() === $this) {
                $userBead->setBead(null);
            }
        }

        return $this;
    }
}
