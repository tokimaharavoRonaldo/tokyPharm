<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockRepository::class)
 */
class Stock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" : 0})
     */
    private $carton;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" : 0})
     */
    private $boite;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" : 0})
     */
    private $plaquette;

    /**
     * @ORM\OneToOne(targetEntity=Medicament::class, inversedBy="the_stock", cascade={"persist", "remove"})
     */
    private $medicament;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarton(): ?int
    {
        return $this->carton;
    }

    public function setCarton(?int $carton): self
    {
        $this->carton = $carton;

        return $this;
    }

    public function getBoite(): ?int
    {
        return $this->boite;
    }

    public function setBoite(?int $boite): self
    {
        $this->boite = $boite;

        return $this;
    }

    public function getPlaquette(): ?int
    {
        return $this->plaquette;
    }

    public function setPlaquette(?int $plaquette): self
    {
        $this->plaquette = $plaquette;

        return $this;
    }

    public function getMedicament(): ?Medicament
    {
        return $this->medicament;
    }

    public function setMedicament(?Medicament $medicament): self
    {
        $this->medicament = $medicament;

        return $this;
    }
}
