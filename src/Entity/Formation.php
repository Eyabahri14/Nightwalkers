<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $price;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $mode;

    /**
     * @ORM\Column(type="datetime")
     */

    private $dateDebut;

    /**
     * @ORM\Column(type="datetime")
     */

    private $dateFin;
    /**
     * @ORM\Column(type="string",length=255)
     */

    private $duree;

    /**
     * @ORM\Column(type="string",length=255)
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
