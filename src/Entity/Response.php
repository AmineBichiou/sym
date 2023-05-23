<?php

namespace App\Entity;

use App\Repository\ResponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResponseRepository::class)]
class Response
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $solution = null;

    #[ORM\Column(type: 'datetime', precision: 6)]
    private ?\DateTimeInterface $dateCreated = null;

    #[ORM\Column(type: 'datetime', precision: 6)]
    private ?\DateTimeInterface $dateModified = null;

    

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Problem $Problem = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSolution(): ?string
    {
        return $this->solution;
    }

    public function setSolution(string $solution): self
    {
        $this->solution = $solution;

        return $this;
    }

    public function getDateCreated(): ?string
    {
        return $this->dateCreated ? $this->dateCreated->format('Y-m-d H:i:s.u') : null;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateModified(): ?string
    {
        return $this->dateModified ? $this->dateModified->format('Y-m-d H:i:s.u') : null;
    }

    public function setDateModified(\DateTimeInterface $dateModified): self
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getProblem(): ?Problem
    {
        return $this->Problem;
    }

    public function setProblem(?Problem $Problem): self
    {
        $this->Problem = $Problem;

        return $this;
    }
}
