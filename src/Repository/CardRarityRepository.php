<?php

namespace App\Repository;

use App\Entity\CardRarity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CardRarity|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardRarity|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardRarity[]    findAll()
 * @method CardRarity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRarityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CardRarity::class);
    }

    // /**
    //  * @return CardRarity[] Returns an array of CardRarity objects
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
    public function findOneBySomeField($value): ?CardRarity
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
