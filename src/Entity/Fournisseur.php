<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=FournisseurRepository::class)
 */
class Fournisseur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

       /**
     * 
     * 
     * @ORM\Column(type="string")
     */
    private $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    function setName($name)
    {
         $this->name=$name;
    }

      /**
     * 
     * 
     * @ORM\Column(type="string")
     */
    private $first_name;

    public function getFirst_name(): ?string
    {
        return $this->first_name;
    }

    function setFirst_name($first_name)
    {
         $this->first_name=$first_name;
    }

      /**
     * 
     * 
     * @ORM\Column(type="text")
     */
    private $contact;

    public function getContact(): ?string
    {
        return $this->contact;
    }

    function setContact($contact)
    {
         $this->contact=$contact;
    }

      /**
     * 
     * 
     * @ORM\Column(type="text")
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
     * this is the owning side .this must exist in the transactions-client relationship
     * @ORM\OneToMany(targetEntity="Transaction",mappedBy="fournisseur")
     * 
     * 
     */
    // private $transaction;

    //   public function __construct()
    // {
    //     $this->transaction=new ArrayCollection();
    // }

    /**
     *  @return mixed    
     */
    // public function getTransaction()
    // {
    //     return $this->transaction;
    // }
}
