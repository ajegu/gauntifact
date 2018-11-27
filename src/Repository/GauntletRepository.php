<?php

namespace App\Repository;

use App\Entity\Gauntlet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Gauntlet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gauntlet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gauntlet[]    findAll()
 * @method Gauntlet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GauntletRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Gauntlet::class);
    }

    // /**
    //  * @return Gauntlet[] Returns an array of Gauntlet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Gauntlet
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
