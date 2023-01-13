<?php

namespace App\Entity;

use App\Repository\TransactionLineRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionLineRepository::class)
 */
class TransactionLine
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Transaction::class, inversedBy="transactionLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_transaction;

    /**
     * @ORM\ManyToOne(targetEntity=Medicament::class, inversedBy="transactionLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_medicament;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2,nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $qty;

      /**
     * @ORM\Column(type="integer")
     */
    private $qty_carton;

      /**
     * @ORM\Column(type="integer")
     */
    private $qty_boite;

      /**
     * @ORM\Column(type="integer")
     */
    private $qty_plaquette;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdTransaction(): ?Transaction
    {
        return $this->id_transaction;
    }

    public function setIdTransaction(?Transaction $id_transaction): self
    {
        $this->id_transaction = $id_transaction;

        return $this;
    }

    public function getIdMedicament(): ?Medicament
    {
        return $this->id_medicament;
    }

    public function setIdMedicament(?Medicament $id_medicament): self
    {
        $this->id_medicament = $id_medicament;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function setQty(int $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    public function getQtyCarton(): ?int
    {
        return $this->qty_carton;
    }

    public function setQtyCarton(int $qty_carton): self
    {
        $this->qty_carton = $qty_carton;

        return $this;
    }


    public function getQtyBoite(): ?int
    {
        return $this->qty_boite;
    }

    public function setQtyBoite(int $qty_boite): self
    {
        $this->qty_boite = $qty_boite;

        return $this;
    }

    public function getQtyPlaquette(): ?int
    {
        return $this->qty_plaquette;
    }

    public function setQtyPlaquette(int $qty_plaquette): self
    {
        $this->qty_plaquette = $qty_plaquette;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
