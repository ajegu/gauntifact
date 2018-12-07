<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Gauntlet;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * @param User $user
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param array|null $statuses
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countGamesByDates(User $user, \DateTime $startDate, \DateTime $endDate, array $statuses = null)
    {
        $qb = $this->createQueryBuilder('g')
            ->select('COUNT(g.id)')
            ->innerJoin(Gauntlet::class, 'ga')
            ->andWhere('g.gauntlet = ga.id')
            ->andWhere('ga.user = :user')
            ->andWhere('g.playedAt >= :startDate')
            ->andWhere('g.playedAt < :endDate')
            ->setParameters([
                'user' => $user->getId(),
                'startDate' => $startDate->format('Y-m-d 00:00:00'),
                'endDate' => $endDate->format('Y-m-d 23:59:59')
            ]);

        if ($statuses !== null) {
            $qb->andWhere('g.status IN (:statuses)')
                ->setParameter('statuses', $statuses);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    // /**
    //  * @return Game[] Returns an array of Game objects
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
    public function findOneBySomeField($value): ?Game
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
