<?php

namespace App\Repository;

use App\Entity\CardSubType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CardSubType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardSubType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardSubType[]    findAll()
 * @method CardSubType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardSubTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CardSubType::class);
    }

    // /**
    //  * @return CardSubType[] Returns an array of CardSubType objects
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
    public function findOneBySomeField($value): ?CardSubType
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
