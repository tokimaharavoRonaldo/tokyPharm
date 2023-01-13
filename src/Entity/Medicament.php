<?php

namespace App\Entity;

use App\Repository\MedicamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MedicamentRepository::class)
 */
class Medicament
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date_peremp;

    /**
     * @ORM\Column(type="text")
     */
    private $cible;
    
        /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $mode_conso;

       /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price_unit_boite;

       /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price_unit_carton;

       /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price_unit_plaquette;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $stock;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $effet_secondaire;

    /**
     * @ORM\ManyToMany(targetEntity=Transaction::class, mappedBy="id_medicament")
     */
    private $transactions;

    /**
     * @ORM\OneToMany(targetEntity=TransactionLine::class, mappedBy="id_medicament")
     */
    private $transactionLines;

    /**
     * @ORM\OneToOne(targetEntity=Stock::class, mappedBy="medicament", cascade={"persist", "remove"})
     */
    private $the_stock;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->transactionLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePeremp(): ?\DateTimeInterface
    {
        return $this->date_peremp;
    }

    public function setDatePeremp(\DateTimeInterface $date_peremp): self
    {
        $this->date_peremp = $date_peremp;

        return $this;
    }

    public function getCible(): ?string
    {
        return $this->cible;
    }

    public function setCible(string $cible): self
    {
        $this->cible = $cible;

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

    public function getModeConso(): ?string
    {
        return $this->mode_conso;
    }

    public function setModeConso(string $mode_conso): self
    {
        $this->mode_conso = $mode_conso;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getEffetSecondaire(): ?string
    {
        return $this->effet_secondaire;
    }

    public function setEffetSecondaire(?string $effet_secondaire): self
    {
        $this->effet_secondaire = $effet_secondaire;

        return $this;
    }

    public function getPriceUnitCarton(): ?string
    {
        return $this->price_unit_carton;
    }

    public function setPriceUnitCarton(string $price_unit_carton): self
    {
        $this->price_unit_carton = $price_unit_carton;

        return $this;
    }

    public function getPriceUnitBoite(): ?string
    {
        return $this->price_unit_boite;
    }

    public function setPriceUnitBoite(string $price_unit_boite): self
    {
        $this->price_unit_boite= $price_unit_boite;

        return $this;
    }

    public function getPriceUnitPlaquette(): ?string
    {
        return $this->price_unit_plaquette;
    }

    public function setPriceUnitPlaquette(string $price_unit_plaquette): self
    {
        $this->price_unit_plaquette = $price_unit_plaquette;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->addIdMedicament($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            $transaction->removeIdMedicament($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, TransactionLine>
     */
    public function getTransactionLines(): Collection
    {
        return $this->transactionLines;
    }

    public function addTransactionLine(TransactionLine $transactionLine): self
    {
        if (!$this->transactionLines->contains($transactionLine)) {
            $this->transactionLines[] = $transactionLine;
            $transactionLine->setIdMedicament($this);
        }

        return $this;
    }

    public function removeTransactionLine(TransactionLine $transactionLine): self
    {
        if ($this->transactionLines->removeElement($transactionLine)) {
            // set the owning side to null (unless already changed)
            if ($transactionLine->getIdMedicament() === $this) {
                $transactionLine->setIdMedicament(null);
            }
        }

        return $this;
    }

    public function getTheStock(): ?Stock
    {
        return $this->the_stock;
    }

    public function setTheStock(?Stock $the_stock): self
    {
        // unset the owning side of the relation if necessary
        if ($the_stock === null && $this->the_stock !== null) {
            $this->the_stock->setMedicament(null);
        }

        // set the owning side of the relation if necessary
        if ($the_stock !== null && $the_stock->getMedicament() !== $this) {
            $the_stock->setMedicament($this);
        }

        $this->the_stock = $the_stock;

        return $this;
    }
}
