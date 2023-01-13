<?php

namespace App\Entity;

use App\Repository\VeritableEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VeritableEntityRepository::class)
 */
class VeritableEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contact;

    /**
     * @ORM\OneToMany(targetEntity=TheNewTransaction::class, mappedBy="veritableEntity")
     */
    private $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    
      /**
     * 
     * 
     * @ORM\Column(type="string")
     */
    private $first_name;

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    function setFirstName($first_name)
    {
         $this->first_name=$first_name;
    }

      /**
     * 
     * 
     * @ORM\Column(type="text")
     * 
     */
    private $address;

    public function getAddress(): ?string
    {
        return $this->address;
    }

    function setAddress($address)
    {
         $this->address=$address;
    }


  /**
     * 
     * 
     * @ORM\Column(type="string")
     */
    private $type;

    public function getType(): ?string
    {
        return $this->type;
    }

    function setType($type)
    {
         $this->type=$type;
    }



    /**
     * @return Collection<int, TheNewTransaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(TheNewTransaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setVeritableEntity($this);
        }

        return $this;
    }

    public function removeTransaction(TheNewTransaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getVeritableEntity() === $this) {
                $transaction->setVeritableEntity(null);
            }
        }

        return $this;
    }
}
