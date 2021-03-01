<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use Gedmo\Mapping\Annotation as Gedmo;
# extensions
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @Gedmo\Slug(fields={"titre"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @Assert\Length(
     *      max = 400,
     *      maxMessage = "Votre texte ne peut dépasser {{ limit }} caractères")
     * 
     * @ORM\Column(type="text")
     */
    private $intro;

    /**
     * @ORM\Column(type="text")
     */
    private $contenu;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="date")
     */
    private $publierAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="date", nullable=true)
     */
    private $modifierAt;

    /**
     * @ORM\ManyToMany(targetEntity=Motcle::class, inversedBy="articles")
     */
    private $motcles;

    /**
     * @ORM\ManyToMany(targetEntity=Categorie::class, inversedBy="articles")
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="article", orphanRemoval=true)
     */
    private $Commentaires;

    /**
     * @ORM\Column(type="boolean")
     */
    private $valide;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="favoris")
     */
    private $favoris;

    /**
     * @ORM\OneToMany(targetEntity=Media::class, mappedBy="article", orphanRemoval=true)
     */
    private $medias;

    public function __construct()
    {
        $this->motcles = new ArrayCollection();
        $this->categorie = new ArrayCollection();
        $this->Commentaires = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->medias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function setIntro(string $intro): self
    {
        $this->intro = $intro;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getPublierAt(): ?\DateTimeInterface
    {
        return $this->publierAt;
    }

    public function setPublierAt(\DateTimeInterface $publierAt): self
    {
        $this->publierAt = $publierAt;

        return $this;
    }

    public function getModifierAt(): ?\DateTimeInterface
    {
        return $this->modifierAt;
    }

    public function setModifierAt(?\DateTimeInterface $modifierAt): self
    {
        $this->modifierAt = $modifierAt;

        return $this;
    }

    /**
     * @return Collection|Motcle[]
     */
    public function getMotcles(): Collection
    {
        return $this->motcles;
    }

    public function addMotcle(Motcle $motcle): self
    {
        if (!$this->motcles->contains($motcle)) {
            $this->motcles[] = $motcle;
        }

        return $this;
    }

    public function removeMotcle(Motcle $motcle): self
    {
        $this->motcles->removeElement($motcle);

        return $this;
    }

    /**
     * @return Collection|Categorie[]
     */
    public function getCategorie(): Collection
    {
        return $this->categorie;
    }

    public function addCategorie(Categorie $categorie): self
    {
        if (!$this->categorie->contains($categorie)) {
            $this->categorie[] = $categorie;
        }

        return $this;
    }

    public function removeCategorie(Categorie $categorie): self
    {
        $this->categorie->removeElement($categorie);

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->Commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->Commentaires->contains($commentaire)) {
            $this->Commentaires[] = $commentaire;
            $commentaire->setArticle($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->Commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getArticle() === $this) {
                $commentaire->setArticle(null);
            }
        }

        return $this;
    }

    public function getValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(bool $valide): self
    {
        $this->valide = $valide;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(User $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
        }

        return $this;
    }

    public function removeFavori(User $favori): self
    {
        $this->favoris->removeElement($favori);

        return $this;
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedia(Media $media): self
    {
        if (!$this->medias->contains($media)) {
            $this->medias[] = $media;
            $media->setArticle($this);
        }

        return $this;
    }

    public function removeMedia(Media $media): self
    {
        if ($this->medias->removeElement($media)) {
            // set the owning side to null (unless already changed)
            if ($media->getArticle() === $this) {
                $media->setArticle(null);
            }
        }

        return $this;
    }
}
