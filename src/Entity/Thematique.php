<?php

namespace App\Entity;

use App\Repository\ThematiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ThematiqueRepository::class)
 */
class Thematique
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *  @Assert\NotBlank(message="Nom thematique doit etre non vide")
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" Entrer un nom au mini de 5 caracteres"
     *
     *     )
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     *  *@Assert\NotBlank(message="image doit etre non vide")
     * @ORM\Column(type="string", length=255)
     */
    private $image;
    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="Discussion", mappedBy="thematique")
     */
    private $discussions;

    public function __construct(){
        $this->discussions = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getDiscussions(): ArrayCollection
    {
        return $this->discussions;
    }

    /**
     * @param ArrayCollection $discussions
     */
    public function setDiscussions(ArrayCollection $discussions): void
    {
        $this->discussions = $discussions;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
