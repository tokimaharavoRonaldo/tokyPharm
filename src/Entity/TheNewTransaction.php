<?php

namespace App\Entity;

use App\Repository\TheNewTransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TheNewTransactionRepository::class)
 */
class TheNewTransaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_transaction;

    /**
     * @ORM\ManyToMany(targetEntity=TheNewProduct::class, inversedBy="transaction_id")
     */
    private $medicament_id;

   

    public function __construct()
    {
        $this->medicament_id = new ArrayCollection();
        $this->realIdentities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateTransaction(): ?\DateTimeInterface
    {
        return $this->date_transaction;
    }

    public function setDateTransaction(?\DateTimeInterface $date_transaction): self
    {
        $this->date_transaction = $date_transaction;

        return $this;
    }

    /**
     * @return Collection<int, TheNewProduct>
     */
    public function getMedicamentId(): Collection
    {
        return $this->medicament_id;
    }

    public function addMedicamentId(TheNewProduct $medicamentId): self
    {
        if (!$this->medicament_id->contains($medicamentId)) {
            $this->medicament_id[] = $medicamentId;
        }

        return $this;
    }

    public function removeMedicamentId(TheNewProduct $medicamentId): self
    {
        $this->medicament_id->removeElement($medicamentId);

        return $this;
    }

 
      /**
     * 
     * 
     * @ORM\Column(type="float")
     */
    private $total_amount;

    public function getTotalAmount(): ?float
    {
        return $this->total_amount;
    }

    function setTotalAmount($total_amount)
    {
         $this->total_amount=$total_amount;
    }

      /**
     * 
     * 
     * @ORM\Column(type="float")
     */
    private $paid_on;

    public function getPaidOn(): ?float
    {
        return $this->paid_on;
    }

    function setPaidOn($paid_on)
    {
         $this->paid_on=$paid_on;
    }




         /**
     * 
     * 
     * @ORM\Column(type="string")
     */
    private $type;


    /**
     * @ORM\ManyToOne(targetEntity=VeritableEntity::class, inversedBy="transactions")
     */
    private $veritableEntity;

    public function getType(): ?string
    {
        return $this->type;
    }

    function setType($type)
    {
         $this->type=$type;
    }



 

  

    public function getVeritableEntity(): ?VeritableEntity
    {
        return $this->veritableEntity;
    }

    public function setVeritableEntity(?VeritableEntity $veritableEntity): self
    {
        $this->veritableEntity = $veritableEntity;

        return $this;
    }


}
