<?php

namespace App\Entity;
use App\Repository\DiscussionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DiscussionRepository::class)
 */
class Discussion
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
    private $date_creation_disc;

    /**
     * * @Assert\NotBlank(message="titre discussion doit etre non vide")
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" Entrer un titre au mini de 5 caracteres"
     *
     *     )
     * @ORM\Column(type="string", length=255)
     */
    private $titre;
    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="Thematique", inversedBy="discussions")
     * @ORM\JoinColumn(name="thematique_id", referencedColumnName="id",onDelete="CASCADE")
     */
    private $thematique;
    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="Commentaire", mappedBy="discussion")
     */
    private $commentaires;

    public function __construct(){
        $this->commentaires = new ArrayCollection();

        $this->date_creation_disc =  new \DateTime();//date('Y-m-d H:i:s');
    }

    /**
     * @return ArrayCollection
     */
    public function getCommentaires(): ArrayCollection
    {
        return $this->commentaires;
    }

    /**
     * @param ArrayCollection $commentaires
     */
    public function setCommentaires(ArrayCollection $commentaires): void
    {
        $this->commentaires = $commentaires;
    }

    /**
     * @return mixed
     */
    public function getThematique()
    {
        return $this->thematique;
    }

    /**
     * @param mixed $thematique
     */
    public function setThematique($thematique): void
    {
        $this->thematique = $thematique;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreationDisc(): \DateTime
    {
        return $this->date_creation_disc;
    }

    /**
     * @param \DateTime $date_creation_disc
     */
    public function setDateCreationDisc(\DateTime $date_creation_disc): void
    {
        $this->date_creation_disc = $date_creation_disc;
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
}
