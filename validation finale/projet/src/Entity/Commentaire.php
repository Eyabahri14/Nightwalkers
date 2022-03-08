<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; // awl 7aja fi control saisi

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation_com;

    /**
     * @Assert\NotBlank(message="Description doit etre non vide")
     * @Assert\Length(
     *      min = 5,
     *      max = 1000,
     *      minMessage = "doit etre >=5 ",
     *      maxMessage = "doit etre <=1000" )
     * @ORM\Column(type="string", length=10000)
     */
    private $description;
    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="Discussion", inversedBy="commentaires")
     * @ORM\JoinColumn(name="discussion_id", referencedColumnName="id",onDelete="CASCADE")
     */
    private $discussion;

    /**
     * @ORM\Column(type="integer")
     */
    private $parent;
    /**
     * @ORM\Column(type="integer")
     */
   private $nblike;

    public function __construct(){
        $this->date_creation_com =  new \DateTime();//date('Y-m-d H:i:s');
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreationCom(): \DateTime
    {
        return $this->date_creation_com;
    }

    /**
     * @param \DateTime $date_creation_com
     */
    public function setDateCreationCom(\DateTime $date_creation_com): void
    {
        $this->date_creation_com = $date_creation_com;
    }

    /**
     * @return mixed
     */
    public function getDiscussion()
    {
        return $this->discussion;
    }

    /**
     * @param mixed $discussion
     */
    public function setDiscussion($discussion): void
    {
        $this->discussion = $discussion;
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
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getNblike()
    {
        return $this->nblike;
    }

    /**
     * @param mixed $nblike
     */
    public function setNblike($nblike): void
    {
        $this->nblike = $nblike;
    }


}
