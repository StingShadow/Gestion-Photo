<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $filename;

    #[ORM\Column(type: 'text', nullable: true)]
    private $legende;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $validation;

    #[ORM\ManyToOne(targetEntity: Codecouleur::class, inversedBy: 'image')]
    private $codecouleur;

    #[ORM\OneToMany(mappedBy: 'Image', targetEntity: Commentaires::class)]
    private $commentaires;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class, mappedBy: 'image')]
    private $utilisateurs;

    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'image')]
    private $tags;

    #[ORM\ManyToOne(targetEntity: Dossier::class, inversedBy: 'images')]
    private $dossier;

    public function __construct()
    {
        $this->utilisateurs = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getLegende(): ?string
    {
        return $this->legende;
    }

    public function setLegende(?string $legende): self
    {
        $this->legende = $legende;

        return $this;
    }

    public function getValidation(): ?bool
    {
        return $this->validation;
    }

    public function setValidation(?bool $validation): self
    {
        $this->validation = $validation;

        return $this;
    }

    public function getCodecouleur(): ?Codecouleur
    {
        return $this->codecouleur;
    }

    public function setCodecouleur(?Codecouleur $codecouleur): self
    {
        $this->codecouleur = $codecouleur;

        return $this;
    }

    public function getCommentaires(): ?Commentaires
    {
        return $this->commentaires;
    }

    public function setCommentaires(?Commentaires $commentaires): self
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): self
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs[] = $utilisateur;
            $utilisateur->addImage($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            $utilisateur->removeImage($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addImage($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeImage($this);
        }

        return $this;
    }

    public function getDossier(): ?dossier
    {
        return $this->dossier;
    }

    public function setDossier(?dossier $dossier): self
    {
        $this->dossier = $dossier;

        return $this;
    }
}
