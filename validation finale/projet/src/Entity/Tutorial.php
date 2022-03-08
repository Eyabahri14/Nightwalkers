<?php

namespace App\Entity;

use App\Repository\TutorialRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TutorialRepository::class)
 */
class Tutorial
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $video;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $image;


    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="You must select a Date for your tutorial !")
     */
    private $dateTuto;

    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank(message="You must select a Category for your tutorial !")
     */
    private $category;

    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank(message="Title can not be empty !")
     */
    private $titre;

    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank(message="Description can not be empty !")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Prix can not be empty !")
     * @Assert\Positive(message="Price should be >0")
     *
     */
    private $prix;

    
    public function getId()
    {
        return $this->id;
    }


    public function setId($id): void
    {
        $this->id = $id;
    }


    public function getVideo()
    {
        return $this->video;
    }

    public function setVideo($video): void
    {
        $this->video = $video;
    }


    public function getDateTuto()
    {
        return $this->dateTuto;
    }


    public function setDateTuto($dateTuto): void
    {
        $this->dateTuto = $dateTuto;
    }


    public function getCategory()
    {
        return $this->category;
    }


    public function setCategory($category): void
    {
        $this->category = $category;
    }


    public function getTitre()
    {
        return $this->titre;
    }


    public function setTitre($titre): void
    {
        $this->titre = $titre;
    }


    public function getDescription()
    {
        return $this->description;
    }


    public function setDescription($description): void
    {
        $this->description = $description;
    }


    public function getPrix()
    {
        return $this->prix;
    }


    public function setPrix($prix): void
    {
        $this->prix = $prix;
    }


    public function getImage()
    {
        return $this->image;
    }


    public function setImage($image): void
    {
        $this->image = $image;
    }




}
