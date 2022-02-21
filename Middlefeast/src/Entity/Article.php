<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


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
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 8 , minMessage="longeur du titre doit etre plus que 8 caractéres")
     * @Assert\NotNull (message="champ null")
     *
     */

    private $name;



    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 30 , minMessage="longeur du titre doit etre plus que 30 caractéres")
     * @Assert\NotNull (message="champ null")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\ManyToMany(targetEntity=Userfavoris::class, mappedBy="article")
     */
    private $userfavoris;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;


    public function __construct()
    {
        $this->userfavoris = new ArrayCollection();
    }










    public function getId(): ?int
    {
        return $this->id;
    }





    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture( $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }



    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Userfavoris[]
     */
    public function getUserfavoris(): Collection
    {
        return $this->userfavoris;
    }

    public function addUserfavori(Userfavoris $userfavori): self
    {
        if (!$this->userfavoris->contains($userfavori)) {
            $this->userfavoris[] = $userfavori;
            $userfavori->addArticle($this);
        }

        return $this;
    }

    public function removeUserfavori(Userfavoris $userfavori): self
    {
        if ($this->userfavoris->removeElement($userfavori)) {
            $userfavori->removeArticle($this);
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }



}
