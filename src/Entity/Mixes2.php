<?php

namespace App\Entity;

use App\Repository\MixesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MixesRepository::class)]
class Mixes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $mixnr = null;

    #[ORM\Column]
    private ?int $beadnr = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMixnr(): ?int
    {
        return $this->mixnr;
    }

    public function setMixnr(int $mixnr): static
    {
        $this->mixnr = $mixnr;

        return $this;
    }

    public function getBeadnr(): ?int
    {
        return $this->beadnr;
    }

    public function setBeadnr(int $beadnr): static
    {
        $this->beadnr = $beadnr;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
