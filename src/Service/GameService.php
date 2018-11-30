<?php
/**
 * Created by PhpStorm.
 * User: allan
 * Date: 2018-11-28
 * Time: 21:07
 */

namespace App\Service;


use App\Entity\Game;
use App\Entity\Gauntlet;
use App\Exception\GameNotNullException;
use App\Exception\GauntletLockException;
use App\Exception\GauntletMaxGameException;
use Doctrine\ORM\EntityManagerInterface;

class GameService
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
     * @param Game $game
     * @throws GameNotNullException
     * @throws GauntletMaxGameException
     */
    public function create(Game $game)
    {
        if ($game->getGauntlet() === null) {
            throw new GameNotNullException('L\'affrontement ne peut pas être null');
        }

        $gauntlet = $game->getGauntlet();

        // On contrôle que le nombre de game de l'affrontement est < à 7
        if (count($gauntlet->getGames()) == 7) {
            throw new GauntletMaxGameException('Le nombre maximum de game (7) pour un affrontement est atteint');
        }

        if (count($gauntlet->getGamesWon()) === 5) {
            throw new GauntletMaxGameException('Le nombre maximum de games gagnées (5) pour un affrontement est atteint');
        }

        if (count($gauntlet->getGamesLost()) === 2) {
            throw new GauntletMaxGameException('Le nombre maximum de games perdues (2) pour un affrontement est atteint');
        }

        $this->manager->persist($game);

        // On ajoute la game à l'affrontement
        $gauntlet->addGame($game);

        // Si le nombre max de game est atteint, on met à jour le statut de l'affrontement
        if ($gauntlet->isPossibleToAddGame() === false) {
            $gauntlet->setStatus(Gauntlet::STATUS_FINISH);
            $this->manager->persist($gauntlet);
        }

        $this->manager->flush();
    }

    public function delete(Game $game)
    {
        if ($game->getGauntlet()->isLock()) {
            throw new GauntletLockException(sprintf('L\'affrontement #%s est vérouillé en modification', $game->getGauntlet()->getId()));
        }

        $this->manager->remove($game);
        $this->manager->flush();
    }
}