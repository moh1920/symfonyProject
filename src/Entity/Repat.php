<?php

namespace App\Entity;

use App\Repository\RepatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RepatRepository::class)]
class Repat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;  // Nom de la clé primaire est idRepas

    #[ORM\Column(length: 255)]
    private ?string $nomRepat = null;

    /**
     * @var Collection<int, Menu>
     */
    #[ORM\ManyToMany(targetEntity: Menu::class, mappedBy: 'repats')]
    private Collection $menus;

    public function __construct()
    {
        $this->menus = new ArrayCollection();
    }

    // Accesseur pour l'ID
    public function getId(): ?int  // Mise à jour du nom de la méthode pour refléter le nom de la propriété
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

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): static
    {
        if (!$this->menus->contains($menu)) {
            $this->menus->add($menu);
            $menu->addRepat($this);
        }
        return $this;
    }

    public function removeMenu(Menu $menu): static
    {
        if ($this->menus->removeElement($menu)) {
            $menu->removeRepat($this);
        }
        return $this;
    }
}
