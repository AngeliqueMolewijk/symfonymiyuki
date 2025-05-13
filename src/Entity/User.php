<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]

#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 180)]
    private ?string $name = null;


    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;


    #[ORM\Column]
    private ?\DateTime $agreedTermsAt = null;

    /**
     * @var Collection<int, UserBead>
     */
    #[ORM\OneToMany(targetEntity: UserBead::class, mappedBy: 'user')]
    private Collection $userBeads;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\OneToMany(targetEntity: Project::class, mappedBy: 'user')]
    private Collection $projects;

    public function __construct()
    {
        $this->userBeads = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }


    // /**
    //  * @var Collection<int, Bead>
    //  */
    // #[ORM\ManyToMany(targetEntity: Bead::class, mappedBy: 'user')]
    // private Collection $beads;

    // public function __construct()
    // {
    //     $this->beads = new ArrayCollection();
    // }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getAgreedTermsAt(): ?\DateTimeImmutable
    {
        return $this->agreedTermsAt;
    }

    public function AgreedTerms()
    {
        $this->agreedTermsAt = new \DateTime();

        return $this;
    }
    public function isGranted($role)
    {
        return in_array($role, $this->getRoles());
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    // /**
    //  * @return Collection<int, Bead>
    //  */
    // public function getBeads(): Collection
    // {
    //     return $this->beads;
    // }

    // public function addBead(Bead $bead): static
    // {
    //     if (!$this->beads->contains($bead)) {
    //         $this->beads->add($bead);
    //         $bead->addUser($this);
    //     }

    //     return $this;
    // }

    // public function removeBead(Bead $bead): static
    // {
    //     if ($this->beads->removeElement($bead)) {
    //         $bead->removeUser($this);
    //     }

    //     return $this;
    // }

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
            $userBead->setUser($this);
        }

        return $this;
    }

    public function removeUserBead(UserBead $userBead): static
    {
        if ($this->userBeads->removeElement($userBead)) {
            // set the owning side to null (unless already changed)
            if ($userBead->getUser() === $this) {
                $userBead->setUser(null);
            }
        }

        return $this;
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
            $project->setUser($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getUser() === $this) {
                $project->setUser(null);
            }
        }

        return $this;
    }


}
