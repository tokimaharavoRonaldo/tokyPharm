<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Transaction $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Transaction $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Transaction[] Returns an array of Transaction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Transaction
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function search_transaction($term,$type){
        $qb = $this->createQueryBuilder('query');
        // $qb->select('cl.type');
        $qb->where("query.type = ",$type);
        $qb->join("query.id_contact",'contact');
        $qb->join("query.transactionLines",'tr_line');
        $qb->join("tr_line.id_medicament",'medicament');
         $qb->where('medicament.name LIKE :term');
      $qb ->setParameter('term','%'.$term.'%');
       $qb->orWhere('contact.name LIKE :term2');
      $qb ->setParameter('term2','%'.$term.'%');
      $qb->orderBy('query.id','DESC');

    //   $qb->orWhere('purchasse.s LIKE :term2');
    //   $qb ->setParameter('term2','%'.$term.'%');
        $result=$qb->getQuery()->getResult();
        return $result;
    }
}
