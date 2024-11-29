<?php

namespace App\Entity;

use App\Repository\RecuRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecuRepository::class)]
class Recu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $etudiant = null;

    #[ORM\Column(length: 255)]
    private ?string $menu = null;

    #[ORM\Column]
    private ?float $prixRecu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtudiant(): ?string
    {
        return $this->etudiant;
    }

    public function setEtudiant(string $etudiant): static
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    public function getMenu(): ?string
    {
        return $this->menu;
    }

    public function setMenu(string $menu): static
    {
        $this->menu = $menu;

        return $this;
    }

    public function getPrixRecu(): ?float
    {
        return $this->prixRecu;
    }

    public function setPrixRecu(float $prixRecu): static
    {
        $this->prixRecu = $prixRecu;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }
}
