<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Gauntlet;
use App\Form\GameType;
use App\Service\DeckService;
use App\Service\GameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /**
     * @Route("/add-game/{id}", name="app_game_add")
     */
    public function add(Request $request, Gauntlet $gauntlet, DeckService $deckService, GameService $gameService)
    {
        $game = new Game();

        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deckCode = $form->get('deckCode')->getData();

            if ($deckCode !== null) {
                $deck = $deckService->createDeckFromCode($deckCode);
                $game->setOpposingDeck($deck);
            }

            $game->setGauntlet($gauntlet);

            $gameService->create($game);

            return new JsonResponse([
                'success' => true,
                'message' => 'success.add_game'
            ]);

        }

        return $this->render('game/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
