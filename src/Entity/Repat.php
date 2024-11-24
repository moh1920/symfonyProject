<?php

namespace App\Entity;

use App\Repository\RepatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RepatRepository::class)]
class Repat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomRepat = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $estDisponible = null;

    /**
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     message = "Le prix ne peut pas être négatif."
     * )
     */
    #[ORM\Column(length: 255)]
    private ?string $prixRepas = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomRepat(): ?string
    {
        return $this->nomRepat;
    }

    public function setNomRepat(string $nomRepat): static
    {
        $this->nomRepat = $nomRepat;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isEstDisponible(): ?bool
    {
        return $this->estDisponible;
    }

    public function setEstDisponible(bool $estDisponible): static
    {
        $this->estDisponible = $estDisponible;

        return $this;
    }

    public function getPrixRepas(): ?string
    {
        return $this->prixRepas;
    }

    public function setPrixRepas(string $prixRepas): static
    {
        $this->prixRepas = $prixRepas;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
