<?php

namespace App\Entity;

use App\Repository\ListeRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use \ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=ListeRepository::class)
  * @ApiResource(
 *    attributes={
 *      "order"={"publishedAt":"DESC"}
 * },
 *    normalizationContext={"groups"={"read:comment"}},
 *   collectionOperations={"get"},
 *   itemOperations={"get"}
 * )
 * @ApiFilter(SearchFilter::class)
 */
class Liste
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

    //   /**
    //  * 
    //  * 
    //  * @ORM\Column(type="string")
    //  */
    // private $title;

    // public function getTitle(): ?string
    // {
    //     return $this->title;
    // }

    // public function setTitle($title)
    // {
    //      $this->title=$title;
    // }

    //   /**
    //  * @ORM\Id
    //  * @ORM\GeneratedValue
    //  * @ORM\Column(type="string")
    //  */
    // private $category;

    // public function getCategory(): ?string
    // {
    //     return $this->category;
    // }

    // public function setCategory($category)
    // {
    //      $this->category=$category;
    // }

    //   /**
    //  * @ORM\Id
    //  * @ORM\GeneratedValue
    //  * @ORM\Column(type="date")
    //  */
    // private $dateSortie;

    // public function getDateSortie(): ?DateTime
    // {
    //     return $this->dateSortie;
    // }

    // function setDateSortie($dateSortie)
    // {
    //      $this->dateSortie=$dateSortie;
    // }

    //   /**
    //  * @ORM\Id
    //  * @ORM\GeneratedValue
    //  * @ORM\Column(type="integer")
    //  */
    // private $acteurId;

    // public function getActeurId(): ?int
    // {
    //     return $this->acteurId;
    // }
    
    // function setActeurId($acteurId)
    // {
    //      $this->acteurId=$acteurId;
    // }

    //    /**
    //  * @ORM\Id
    //  * @ORM\GeneratedValue
    //  * @ORM\Column(type="integer")
    //  */
    // private $producteurId;

    // public function getProducteurId(): ?int
    // {
    //     return $this->producteurId;
    // }
    
    // function setProducteurId($producteurId)
    // {
    //      $this->producteurId=$producteurId;
    // }
}
