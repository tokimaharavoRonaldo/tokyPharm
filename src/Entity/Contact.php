<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
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
     * @ORM\Column(type="string", length=255)
     */
    private $type;

        /**
     * 
     * 
     * @ORM\Column(type="text", nullable=true)
     * 
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $surname;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_entreprise;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $entreprise_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $other_field;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="id_contact")
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

  

    public function getAddress(): ?string
    {
        return $this->address;
    }

    function setAddress($address)
    {
         $this->address=$address;
    }


    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getIsEntreprise(): ?bool
    {
        return $this->is_entreprise;
    }

    public function setIsEntreprise(bool $is_entreprise): self
    {
        $this->is_entreprise = $is_entreprise;

        return $this;
    }

    public function getEntrepriseName(): ?string
    {
        return $this->entreprise_name;
    }

    public function setEntrepriseName(?string $entreprise_name): self
    {
        $this->entreprise_name = $entreprise_name;

        return $this;
    }

    public function getOtherField(): ?string
    {
        return $this->other_field;
    }

    public function setOtherField(?string $other_field): self
    {
        $this->other_field = $other_field;

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
            $transaction->setIdContact($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getIdContact() === $this) {
                $transaction->setIdContact(null);
            }
        }

        return $this;
    }

}
