<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Contact::class, inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_contact;

    /**
     * @ORM\ManyToMany(targetEntity=Medicament::class, inversedBy="transactions")
     */
    private $id_medicament;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_transaction;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

     /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $num_facture;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price_total;
    
        /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $total_paid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status_payment;

       /**
     * @ORM\Column(type="string", length=255)
     */
    private $mode_payment;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\OneToMany(targetEntity=TransactionLine::class, mappedBy="id_transaction")
     */
    private $transactionLines;

    public function __construct()
    {
        $this->id_medicament = new ArrayCollection();
        $this->transactionLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumFacture(): ?string
    {
        return $this->num_facture;
    }

    public function setNumFacture(string $num_facture): self
    {
        $this->num_facture = $num_facture;

        return $this;
    }

    public function getIdContact(): ?Contact
    {
        return $this->id_contact;
    }

    public function setIdContact(?Contact $id_contact): self
    {
        $this->id_contact = $id_contact;

        return $this;
    }

    /**
     * @return Collection<int, Medicament>
     */
    public function getIdMedicament(): Collection
    {
        return $this->id_medicament;
    }

    public function addIdMedicament(Medicament $idMedicament): self
    {
        if (!$this->id_medicament->contains($idMedicament)) {
            $this->id_medicament[] = $idMedicament;
        }

        return $this;
    }

    public function removeIdMedicament(Medicament $idMedicament): self
    {
        $this->id_medicament->removeElement($idMedicament);

        return $this;
    }

    public function getDateTransaction(): ?\DateTimeInterface
    {
        return $this->date_transaction;
    }

    public function setDateTransaction(\DateTimeInterface $date_transaction): self
    {
        $this->date_transaction = $date_transaction;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPriceTotal(): ?string
    {
        return $this->price_total;
    }

    public function setPriceTotal(string $price_total): self
    {
        $this->price_total = $price_total;

        return $this;
    }

    public function getTotalPaid(): ?string
    {
        return $this->total_paid;
    }

    public function setTotalPaid(string $total_paid): self
    {
        $this->total_paid = $total_paid;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatusPayment(): ?string
    {
        return $this->status_payment;
    }



    public function setStatusPayment(string $status_payment): self
    {
        $this->status_payment = $status_payment;

        return $this;
    }

    public function getModePayment(): ?string
    {
        return $this->mode_payment;
    }

    public function setModePayment(string $mode_payment): self
    {
        $this->mode_payment = $mode_payment;

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
            $transactionLine->setIdTransaction($this);
        }

        return $this;
    }

    public function removeTransactionLine(TransactionLine $transactionLine): self
    {
        if ($this->transactionLines->removeElement($transactionLine)) {
            // set the owning side to null (unless already changed)
            if ($transactionLine->getIdTransaction() === $this) {
                $transactionLine->setIdTransaction(null);
            }
        }

        return $this;
    }
}
