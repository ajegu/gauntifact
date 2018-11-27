<?php

namespace App\Repository;

use App\Entity\GauntletType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GauntletType|null find($id, $lockMode = null, $lockVersion = null)
 * @method GauntletType|null findOneBy(array $criteria, array $orderBy = null)
 * @method GauntletType[]    findAll()
 * @method GauntletType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GauntletTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GauntletType::class);
    }

    // /**
    //  * @return GauntletType[] Returns an array of GauntletType objects
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
    public function findOneBySomeField($value): ?GauntletType
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
