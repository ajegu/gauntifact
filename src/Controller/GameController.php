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
        $game = new Game();

        $form = $this->createForm(GameType::class, $game, [
            'gauntlet' => $gauntlet
        ]);
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
     * @param DeckService $deckService
     * @param GameService $gameService
     * @param TranslatorInterface $translator
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \App\Exception\CardNotFoundException
     * @throws \App\Exception\GauntletMaxGameException
     *
     * @Route("/edit-game/{id}", name="app_game_edit")
     */
    public function edit(Request $request, Game $game, DeckService $deckService, GameService $gameService, TranslatorInterface $translator)
    {
        $form = $this->createForm(GameType::class, $game, [
            'gauntlet' => $game->getGauntlet(),
            'gameId' => $game->getId()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deckCode = $form->get('deckCode')->getData();

            if ($game->getOpposingDeck() !== null) {
                if ($deckCode !== $game->getOpposingDeck()->getCode()) {
                    // On supprime le deck adverse si il est différent du deck saisit
                    $gameService->deleteOpposingDeck($game);
                }

            }

            // On crée le deck adverse si le code est renseigné
            if ($deckCode !== null) {
                $deck = $deckService->createDeckFromCode($deckCode);
                $game->setOpposingDeck($deck);
            }

            $gameService->edit($game);

            $this->addFlash(
                'success',
                $translator->trans('success.edit_game')
            );

            return new JsonResponse([
                'success' => true
            ]);
        }

        return $this->render('game/edit.html.twig', [
            'form' => $form->createView(),
            'game' => $game
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
