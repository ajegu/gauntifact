<?php

namespace App\Repository;

use App\Entity\CardSet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CardSet|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardSet|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardSet[]    findAll()
 * @method CardSet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardSetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CardSet::class);
    }

    // /**
    //  * @return CardSet[] Returns an array of CardSet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CardSet
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
