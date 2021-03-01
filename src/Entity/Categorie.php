<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
# extensions
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="sous_categorie")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Categorie::class, mappedBy="parent")
     */
    private $sous_categorie;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, mappedBy="categorie")
     */
    private $articles;

    public function __construct()
    {
        $this->sous_categorie = new ArrayCollection();
        $this->articles = new ArrayCollection();
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

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSousCategorie(): Collection
    {
        return $this->sous_categorie;
    }

    public function addSousCategorie(self $sousCategorie): self
    {
        if (!$this->sous_categorie->contains($sousCategorie)) {
            $this->sous_categorie[] = $sousCategorie;
            $sousCategorie->setParent($this);
        }

        return $this;
    }

    public function removeSousCategorie(self $sousCategorie): self
    {
        if ($this->sous_categorie->removeElement($sousCategorie)) {
            // set the owning side to null (unless already changed)
            if ($sousCategorie->getParent() === $this) {
                $sousCategorie->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addCategorie($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            $article->removeCategorie($this);
        }

        return $this;
    }
}
