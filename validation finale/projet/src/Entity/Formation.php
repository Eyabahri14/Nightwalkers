<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="You must select a Price for your Course !")
     */
    private $price;

    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank(message="You must select a Mode for your Course !")
     */
    private $mode;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="You must select a Start Date for your Course !")
     */

    private $dateDebut;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="You must select an End Date for your Course !")

     */

    private $dateFin;
    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank(message="You must select a Duration for your Course !")

     */

    private $duree;

    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank(message="You must select a Description for your Course !")

     */
    private $description;


    public function getId()
    {
        return $this->id;
    }


    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getPrice()
    {
        return $this->price;
    }


    public function setPrice($price): void
    {
        $this->price = $price;
    }

    public function getMode()
    {
        return $this->mode;
    }


    public function setMode($mode): void
    {
        $this->mode = $mode;
    }


    public function getDateDebut()
    {
        return $this->dateDebut;
    }


    public function setDateDebut($dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }


    public function getDateFin()
    {
        return $this->dateFin;
    }


    public function setDateFin($dateFin): void
    {
        $this->dateFin = $dateFin;
    }


    public function getDuree()
    {
        return $this->duree;
    }


    public function setDuree($duree): void
    {
        $this->duree = $duree;
    }


    public function getDescription()
    {
        return $this->description;
    }


    public function setDescription($description): void
    {
        $this->description = $description;
    }













}
