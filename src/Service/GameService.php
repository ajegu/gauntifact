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

        $this->checkMaxGame($game);

        $game->setNumber(count($gauntlet->getGames()) + 1);

        $this->manager->persist($game);
        $this->manager->flush();

        // On ajoute la game à l'affrontement
        $gauntlet->addGame($game);
        $this->lockGauntletIfFinish($gauntlet);
    }

    /**
     * @param Game $game
     * @throws GauntletMaxGameException
     */
    private function checkMaxGame(Game $game)
    {
        $gauntlet = $game->getGauntlet();

        $gamesCount = 0;
        $gamesWonCount = 0;
        $gamesLoseCount = 0;
        foreach ($gauntlet->getGames() as $gauntletGame) {

            // On ignore la game en cours de modification
            if ($gauntletGame->getId() === $game->getId()) {
                continue;
            }

            $gamesCount++;

            if ($gauntletGame->getStatus() === Game::STATUS_WIN) {
                $gamesWonCount++;
            } else {
                $gamesLoseCount++;
            }
        }

        // On contrôle que le nombre de game de l'affrontement est < à 7
        if ($gamesCount == 7) {
            throw new GauntletMaxGameException('Le nombre maximum de game (7) pour un affrontement est atteint');
        }

        if ($gamesWonCount === 5 && $game->getStatus() === Game::STATUS_WIN) {
            throw new GauntletMaxGameException('Le nombre maximum de games gagnées (5) pour un affrontement est atteint');
        }

        if ($gamesLoseCount === 2 && in_array($game->getStatus(), [Game::STATUS_DRAW, Game::STATUS_LOSE])) {
            throw new GauntletMaxGameException('Le nombre maximum de games perdues (2) pour un affrontement est atteint');
        }
    }

    /**
     * @param Game $game
     * @throws GauntletLockException
     */
    public function delete(Game $game)
    {
        $gauntlet = $game->getGauntlet();

        if ($gauntlet->isLock()) {
            throw new GauntletLockException(sprintf('L\'affrontement #%s est vérouillé en modification', $game->getGauntlet()->getId()));
        }

        $this->manager->remove($game);
        $this->manager->flush();

        $gauntlet->removeGame($game);

        // On vérouille l'affrontement si celui-ci est terminé
        $this->lockGauntletIfFinish($gauntlet);

        // On remet à jour la numérotation des games
        $this->refreshNumber($game);
    }

    /**
     * @param Game $deletedGame
     */
    private function refreshNumber(Game $deletedGame)
    {
        $gauntlet = $deletedGame->getGauntlet();

        foreach ($gauntlet->getGames() as $game) {
            if ($game->getNumber() > $deletedGame->getNumber()) {
                $game->setNumber($game->getNumber() - 1);
                $this->manager->persist($game);
            }
        }

        $this->manager->flush();
    }

    /**
     * @param Gauntlet $gauntlet
     */
    private function lockGauntletIfFinish(Gauntlet $gauntlet)
    {
        // Si le nombre max de game est atteint, on met à jour le statut de l'affrontement
        if ($gauntlet->isPossibleToAddGame() === false) {
            $gauntlet->setStatus(Gauntlet::STATUS_FINISH);
            $this->manager->persist($gauntlet);
            $this->manager->flush();
        }
    }

    /**
     * @param Game $game
     */
    public function deleteOpposingDeck(Game $game)
    {
        $opposingDeck = $game->getOpposingDeck();

        $game->setOpposingDeck(null);

        $this->manager->persist($game);
        $this->manager->remove($opposingDeck);

        $this->manager->flush();
    }

    /**
     * @param Game $game
     * @throws GauntletMaxGameException
     */
    public function edit(Game $game)
    {
        $this->checkMaxGame($game);

        $this->manager->persist($game);
        $this->manager->flush();

        $this->lockGauntletIfFinish($game->getGauntlet());
    }
}