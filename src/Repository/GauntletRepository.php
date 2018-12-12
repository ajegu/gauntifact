<?php

namespace App\Repository;

use App\Entity\Gauntlet;
use App\Entity\GauntletType;
use App\Entity\User;
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

    /**
     * @param User $user
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param GauntletType|null $gauntletType
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countGauntletByDates(User $user, \DateTime $startDate, \DateTime $endDate, GauntletType $gauntletType = null)
    {
        $qb = $this->createQueryBuilder('g')
            ->select('COUNT(g.id) as countGauntlet')
            ->andWhere('g.playedAt >= :startDate')
            ->andWhere('g.playedAt < :endDate')
            ->andWhere('g.user = :user')
            ->setParameters([
                'user' => $user->getId(),
                'startDate' => $startDate->format('Y-m-d 00:00:00'),
                'endDate' => $endDate->format('Y-m-d 23:59:59')
            ]);

        if ($gauntletType !== null) {
            $qb->andWhere('g.type = :gauntletType')
                ->setParameter('gauntletType', $gauntletType);
        }

        return $qb->getQuery()->getSingleScalarResult();
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
