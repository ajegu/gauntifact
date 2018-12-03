<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Gauntlet;
use App\Form\GameType;
use App\Service\DeckService;
use App\Service\GameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class GameController extends AbstractController
{

    /**
     * @param Request $request
     * @param Gauntlet $gauntlet
     * @param DeckService $deckService
     * @param GameService $gameService
     * @param TranslatorInterface $translator
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \App\Exception\CardNotFoundException
     * @throws \App\Exception\GameNotNullException
     * @throws \App\Exception\GauntletMaxGameException
     *
     * @Route("/add-game/{id}", name="app_game_add")
     */
    public function add(Request $request, Gauntlet $gauntlet, DeckService $deckService, GameService $gameService, TranslatorInterface $translator)
    {
        throw new \Exception();
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

            $this->addFlash(
                'success',
                $translator->trans('success.add_game')
            );

            return new JsonResponse([
                'success' => true
            ]);
        }

        return $this->render('game/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @param Request $request
     * @param Game $game
     * @param GameService $gameService
     * @param TranslatorInterface $translator
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \App\Exception\GauntletLockException
     *
     * @Route("/delete-game/{id}", name="app_game_delete")
     */
    public function delete(Request $request, Game $game, GameService $gameService, TranslatorInterface $translator)
    {
        $form = $this->createForm(FormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $gameService->delete($game);

            $this->addFlash(
                'success',
                $translator->trans('success.delete_game')
            );

            return new JsonResponse([
                'success' => true
            ]);
        }

        return $this->render('game/delete.html.twig', [
            'form' => $form->createView(),
            'game' => $game
        ]);
    }
}
