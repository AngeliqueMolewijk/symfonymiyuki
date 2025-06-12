<?php

namespace App\Entity;

use App\Repository\UserBeadRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserBeadRepository::class)]
class UserBead
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userBeads')]
    private ?user $user = null;

    #[ORM\ManyToOne(inversedBy: 'userBeads')]
    private ?bead $bead = null;

    #[ORM\Column(nullable: true)]
    private ?int $stock = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $controlled = null;

    // public function __construct()
    // {
    //     $this->controlled = new \DateTime();
    // }
    // private ?int $originalStock = null;

    // #[ORM\PostLoad]
    // public function storeOriginalStock(): void
    // {
    //     $this->originalStock = $this->stock;
    // }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBead(): ?bead
    {
        return $this->bead;
    }

    public function setBead(?bead $bead): static
    {
        $this->bead = $bead;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): static
    {
        $this->stock = $stock;
        // $this->controlled = new \DateTimeImmutable();

        return $this;
    }

    public function getControlled(): ?\DateTimeInterface
    {
        return $this->controlled;
    }

    public function setControlled(?\DateTimeInterface $controlled): static
    {
        $this->controlled = $controlled;

        return $this;
    }


    public function setControlledAt(): void
    {
        // dd($this->originalStock, $this->stock);
        // if ($this->stock !== $this->originalStock) {
        $this->controlled = new \DateTimeImmutable();
        // }
    }
}
