<?php
/**
 * Created by PhpStorm.
 * User: allan
 * Date: 2018-11-28
 * Time: 21:07
 */

namespace App\Service;


use App\Entity\Game;
use App\Exception\GameNotNullException;
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
     */
    public function create(Game $game)
    {
        if ($game->getGauntlet() === null) {
            throw new GameNotNullException('L\'affrontement ne peut pas être null');
        }

        $this->manager->persist($game);
        $this->manager->flush();
    }
}