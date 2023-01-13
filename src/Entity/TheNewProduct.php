<?php

namespace App\Entity;

use App\Repository\TheNewProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TheNewProductRepository::class)
 */
class TheNewProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=TheNewStock::class, cascade={"persist", "remove"})
     */
    private $Stock_id;

    /**
     * @ORM\ManyToMany(targetEntity=TheNewTransaction::class, mappedBy="medicament_id")
     */
    private $transaction_id;

    public function __construct()
    {
        $this->transaction_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStockId(): ?TheNewStock
    {
        return $this->Stock_id;
    }

    public function setStockId(?TheNewStock $Stock_id): self
    {
        $this->Stock_id = $Stock_id;

        return $this;
    }

    /**
     * @return Collection<int, TheNewTransaction>
     */
    public function getTransactionId(): Collection
    {
        return $this->transaction_id;
    }

    public function addTransactionId(TheNewTransaction $transactionId): self
    {
        if (!$this->transaction_id->contains($transactionId)) {
            $this->transaction_id[] = $transactionId;
            $transactionId->addMedicamentId($this);
        }

        return $this;
    }

    public function removeTransactionId(TheNewTransaction $transactionId): self
    {
        if ($this->transaction_id->removeElement($transactionId)) {
            $transactionId->removeMedicamentId($this);
        }

        return $this;
    }

     /**
     * 
     * 
     * @ORM\Column(type="string")
     */
    private $mode_emploi;

    public function getModeEmploi(): ?string
    {
        return $this->mode_emploi;
    }

    function setModeEmploi($mode_emploi)
    {
         $this->mode_emploi=$mode_emploi;
    }

    /**
     * 
     * 
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_peremp;

    public function getDatePeremp(): ?\DateTimeInterface
    {
        return $this->date_peremp;
    }

    function setDatePeremp($date_peremp)
    {
         $this->date_peremp=$date_peremp;
    }

    /**
     * 
     * 
     * @ORM\Column(type="string")
     */
    private $cible;

    public function getCible(): ?string
    {
        return $this->cible;
    }

    function setCible($cible)
    {
         $this->cible=$cible;
    }

        /**
     * 
     * 
     * @ORM\Column(type="text")
     */
    private $note;

    public function getNote(): ?string
    {
        return $this->note;
    }

    function setNote($note)
    {
         $this->note=$note;
    }


}
