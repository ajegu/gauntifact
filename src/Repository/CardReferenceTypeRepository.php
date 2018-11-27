<?php

namespace App\Repository;

use App\Entity\CardReferenceType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CardReferenceType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardReferenceType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardReferenceType[]    findAll()
 * @method CardReferenceType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardReferenceTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CardReferenceType::class);
    }

    // /**
    //  * @return CardReferenceType[] Returns an array of CardReferenceType objects
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
    public function findOneBySomeField($value): ?CardReferenceType
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
