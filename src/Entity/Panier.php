<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PanierRepository::class)
 */
class Panier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="qte", type="integer", nullable=false)
     * @Assert\NotBlank(message="Please enter a number.")
     */
    private $qte;

    /**
     * @var integer
     *
     * @ORM\Column(name="total", type="float", nullable=false)
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Formation")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="formation_id",referencedColumnName="id",nullable=true)
     *})
     */
    private $formation;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tutorial")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="tutorial_id",referencedColumnName="id",nullable=true)
     *})
     */
    private $tutorial;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     *})
     */
    private $user;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getQte(): int
    {
        return $this->qte;
    }

    /**
     * @param int $qte
     */
    public function setQte(int $qte): void
    {
        $this->qte = $qte;
    }

    /**
     */
    public function getFormation(): Formation
    {
        if($this->formation != null)
        {
            return $this->formation;
        }
        return new Formation();

    }

    /**
     * @param  $formation
     */
    public function setFormation($formation): void
    {
        $this->formation = $formation;
    }

    /**
     *
     */
    public function getTutorial(): Tutorial
    {
        if($this->tutorial !=null)
        {
            return $this->tutorial;
        }else
        {
            return new Tutorial();
        }

    }

    /**
     * @param  $tutorial
     */
    public function setTutorial( $tutorial): void
    {
        $this->tutorial = $tutorial;
    }

    /**
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param  $user
     */
    public function setUser( $user): void
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal(int $total): void
    {
        $this->total = $total;
    }





}
