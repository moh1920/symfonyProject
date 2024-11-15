<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomMenu = null;

    #[ORM\Column(length: 255)]
    private ?string $prixMenu = null;

    #[ORM\Column]
    private ?bool $desponibilite = null;

    /**
     * @var Collection<int, Repat>
     */
    #[ORM\ManyToMany(targetEntity: Repat::class, inversedBy: 'menus')]
    private Collection $repats;

    public function __construct()
    {
        $this->repats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id; // Correction : Utilisation de idMenu
    }

    public function getNomMenu(): ?string
    {
        return $this->nomMenu;
    }

    public function setNomMenu(string $nomMenu): static
    {
        $this->nomMenu = $nomMenu;
        return $this;
    }

    public function getPrixMenu(): ?string
    {
        return $this->prixMenu;
    }

    public function setPrixMenu(string $prixMenu): static
    {
        $this->prixMenu = $prixMenu;
        return $this;
    }

    public function isDesponibilite(): ?bool
    {
        return $this->desponibilite;
    }

    public function setDesponibilite(bool $desponibilite): static
    {
        $this->desponibilite = $desponibilite;
        return $this;
    }

    /**
     * @return Collection<int, Repat>
     */
    public function getRepats(): Collection
    {
        return $this->repats;
    }

    public function addRepat(Repat $repat): static
    {
        if (!$this->repats->contains($repat)) {
            $this->repats->add($repat);
        }
        return $this;
    }

    public function removeRepat(Repat $repat): static
    {
        $this->repats->removeElement($repat);
        return $this;
    }
}
