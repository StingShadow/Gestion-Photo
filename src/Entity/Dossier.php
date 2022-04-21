<?php

namespace App\Entity;

use App\Repository\DossierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DossierRepository::class)]
class Dossier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[ORM\OneToMany(mappedBy: 'dossier', targetEntity: Image::class)]
    private $images;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'dossier')]
    private $sous_dossier;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->sous_dossier = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setDossier($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getDossier() === $this) {
                $image->setDossier(null);
            }
        }

        return $this;
    }

    public function getSousDossier(): ?ArrayCollection
    {
        return $this->sous_dossier;
    }

    public function setSousDossier(?self $sous_dossier): self
    {
        $this->sous_dossier = $sous_dossier;

        return $this;
    }

    public function addSousDossier(self $sousDossier): self
    {
        if (!$this->sous_dossier->contains($sousDossier)) {
            $this->sous_dossier[] = $sousDossier;
            $sousDossier->setSousDossier($this);
        }

        return $this;
    }

    public function removeSousDossier(self $sousDossier): self
    {
        if ($this->sous_dossier->removeElement($sousDossier)) {
            // set the owning side to null (unless already changed)
            if ($sousDossier->getSousDossier() === $this) {
                $sousDossier->setSousDossier(null);
            }
        }

        return $this;
    }
}
