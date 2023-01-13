<?php

namespace App\Entity;

use App\Repository\OrdonnanceRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=OrdonnanceRepository::class)
 */
class Ordonnance
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
     * @ORM\Column(type="integer")
     */
    private $id_client;

    public function getId_client(): ?int
    {
        return $this->id_client;
    }

    function setId_client($id_client)
    {
         $this->id_client=$id_client;
    }
    
        /**
     * 
     * 
     * @ORM\Column(type="integer")
     */
    private $id_medicament;

    public function getId_medicament(): ?int
    {
        return $this->id_medicament;
    }

    function setId_medicament($id_medicament)
    {
         $this->id_medicament=$id_medicament;
    }
}
