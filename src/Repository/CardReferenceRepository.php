<?php

namespace App\Repository;

use App\Entity\CardReference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CardReference|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardReference|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardReference[]    findAll()
 * @method CardReference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardReferenceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CardReference::class);
    }

    // /**
    //  * @return CardReference[] Returns an array of CardReference objects
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
    public function findOneBySomeField($value): ?CardReference
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
