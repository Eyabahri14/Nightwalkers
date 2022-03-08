<?php

namespace App\Entity;

use App\Repository\UserfavorisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserfavorisRepository::class)
 */
class Userfavoris
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, inversedBy="userfavoris",cascade={"remove", "persist"})
     */
    private $article;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="userfav", cascade={"persist", "remove"})
     */
    private $user;

    public function __construct()
    {
        $this->article = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->article->contains($article)) {
            $this->article[] = $article;
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        $this->article->removeElement($article);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setUserfav(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getUserfav() !== $this) {
            $user->setUserfav($this);
        }

        $this->user = $user;

        return $this;
    }


}
