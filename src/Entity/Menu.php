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
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private string $menuNom;

    #[ORM\Column(type: "text")]
    private string $menuDescription;

    #[ORM\Column(type: "float")]
    private float $menuPrix;

    #[ORM\Column(type: "boolean")]
    private bool $menuDisponible;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $menuDateCreation;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $menuDateExpiration = null;

    #[ORM\ManyToMany(targetEntity: Repat::class)]
    #[ORM\JoinTable(name: 'menu_repat')] // Nom de la table intermédiaire
    private Collection $repas;


    public function __construct(float $menuPrix = 0)
    {
        $this->repas = new ArrayCollection();
        $this->menuPrix = $menuPrix; // Initialisation dans le constructeur

    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getMenuNom(): string
    {
        return $this->menuNom;
    }

    public function setMenuNom(string $menuNom): self
    {
        $this->menuNom = $menuNom;

        return $this;
    }

    public function getMenuDescription(): string
    {
        return $this->menuDescription;
    }

    public function setMenuDescription(string $menuDescription): self
    {
        $this->menuDescription = $menuDescription;

        return $this;
    }

    public function getMenuPrix(): float
    {
        return $this->menuPrix;
    }

    public function setMenuPrix(float $menuPrix): self
    {
        $this->menuPrix = $menuPrix;

        return $this;
    }

    public function isMenuDisponible(): bool
    {
        return $this->menuDisponible;
    }

    public function setMenuDisponible(bool $menuDisponible): self
    {
        $this->menuDisponible = $menuDisponible;

        return $this;
    }

    public function getMenuDateCreation(): \DateTimeInterface
    {
        return $this->menuDateCreation;
    }

    public function setMenuDateCreation(\DateTimeInterface $menuDateCreation): self
    {
        $this->menuDateCreation = $menuDateCreation;

        return $this;
    }

    public function getMenuDateExpiration(): ?\DateTimeInterface
    {
        return $this->menuDateExpiration;
    }

    public function setMenuDateExpiration(?\DateTimeInterface $menuDateExpiration): self
    {
        $this->menuDateExpiration = $menuDateExpiration;

        return $this;
    }

       /**
     * @return Collection<int, Repat>
     */
    public function getRepas(): Collection
    {
        return $this->repas;
    }


    public function addRepat(Repat $repat): static
    {
        if (!$this->repas->contains($repat)) {
            $this->repas->add($repat);
        }

        return $this;
    }

    public function removeRepat(Repat $repat): static
    {
        $this->repas->removeElement($repat);

        return $this;
    }
     
}
