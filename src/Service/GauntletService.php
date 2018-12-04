<?php
/**
 * Created by PhpStorm.
 * User: prestasic10
 * Date: 28/11/2018
 * Time: 14:16
 */

namespace App\Service;


use App\Entity\Gauntlet;
use App\Entity\User;
use App\Exception\GauntletLockException;
use App\Exception\GauntletNotNullException;
use App\Exception\GauntletStatusException;
use Doctrine\ORM\EntityManagerInterface;

class GauntletService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * DeckService constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param User $user
     * @return Gauntlet|null|object
     */
    public function getCurrent(User $user)
    {
        $gauntlet = $this->manager->getRepository(Gauntlet::class)
            ->findOneBy([
                'user' => $user,
                'status' => Gauntlet::STATUS_CURRENT
            ]);

        return $gauntlet;
    }
    
    /**
     * @param Gauntlet $gauntlet
     * @throws GauntletNotNullException
     */
    public function create(Gauntlet $gauntlet)
    {
        if ($gauntlet->getUser() === null) {
            throw new GauntletNotNullException("L'utilisateur ne peut pas être null");
        }

        if ($gauntlet->getDeck() === null) {
            throw new GauntletNotNullException("Le deck ne peut pas être null");
        }

        // On calcule le numéro de l'affrontement
        $gauntlets = $this->manager->getRepository(Gauntlet::class)
            ->findBy([
                'user' => $gauntlet->getUser()
            ]);

        $number = count($gauntlets) + 1;
        $gauntlet->setNumber($number);

        $this->manager->persist($gauntlet);
        $this->manager->flush();
    }

    /**
     * @param User $user
     * @return Gauntlet[]
     */
    public function list(User $user)
    {
        $gauntlets = $this->manager->getRepository(Gauntlet::class)
            ->findBy([
                'user' => $user
            ]);

        return $gauntlets;
    }

    /**
     * @param Gauntlet $gauntlet
     * @throws GauntletNotNullException
     */
    public function unlock(Gauntlet $gauntlet)
    {
        $current = $this->getCurrent($gauntlet->getUser());

        if ($current !== null) {
            throw new GauntletNotNullException(
                sprintf('L\utilisateur %s ne peux pas avoir plusieurs affrontements en cours', $gauntlet->getUser()->getId())
            );
        }

        $gauntlet->setStatus(Gauntlet::STATUS_CURRENT);

        $this->manager->persist($gauntlet);
        $this->manager->flush();
    }

    /**
     * @param Gauntlet $gauntlet
     * @throws GauntletLockException
     */
    public function lock(Gauntlet $gauntlet)
    {
        // On contrôle que l'affrontement est bien terminé
        if ($gauntlet->isPossibleToAddGame() === true) {
            throw new GauntletLockException(
                sprintf("L'affrontement %s ne peut pas être vérrouillé car il est toujours possibles d'ajouter des games"), $gauntlet->getId()
            );
        }

        $gauntlet->setStatus(Gauntlet::STATUS_FINISH);

        $this->manager->persist($gauntlet);
        $this->manager->flush();
    }

    /**
     * @param Gauntlet $gauntlet
     * @throws GauntletStatusException
     */
    public function concede(Gauntlet $gauntlet)
    {
        if ($gauntlet->getStatus() !== Gauntlet::STATUS_CURRENT) {
            throw new GauntletStatusException(
                sprintf("L'affrontement %s ne peut pas être abandonné car il n'est pas au statut : %s", $gauntlet->getId(), Gauntlet::STATUS_CURRENT)
            );
        }

        $gauntlet->setStatus(Gauntlet::STATUS_CONCEDED);

        $this->manager->persist($gauntlet);
        $this->manager->flush();
    }
}