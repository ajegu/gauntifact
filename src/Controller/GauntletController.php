<?php

namespace App\Controller;

use App\Entity\Gauntlet;
use App\Form\GauntletType;
use App\Service\DeckService;
use App\Service\GauntletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GauntletController extends AbstractController
{
    /**
     * @Route("/gauntlet", name="gauntlet")
     */
    public function index()
    {
        return $this->render('gauntlet/index.html.twig', [
            'controller_name' => 'GauntletController',
        ]);
    }

    /**
     * @param Request $request
     * @param DeckService $deckService
     * @param GauntletService $gauntletService
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \App\Exception\CardNotFoundException
     * @throws \App\Exception\GauntletNotNullException
     *
     * @Route("/add-gauntlet", name="app_gauntlet_add")
     */
    public function add(Request $request, DeckService $deckService, GauntletService $gauntletService)
    {
        // On check qu'un affrontement "en cours" existe
        $gauntlet = $gauntletService->getCurrent($this->getUser());
        if ($gauntlet !== null) {
            return $this->redirectToRoute('app_gauntlet_show', ['id' => $gauntlet->getId()]);
        }

        // On crée un affrontement
        $gauntlet = new Gauntlet();

        $form = $this->createForm(GauntletType::class, $gauntlet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deckCode = $form->get('deckCode')->getData();
            $deck = $deckService->createDeckFromCode($deckCode);

            $gauntlet->setDeck($deck)
                ->setUser($this->getUser());

            $gauntletService->create($gauntlet);

            return new JsonResponse([
                'success' => true,
                'message' => 'success.add_gauntlet',
                'gauntletId' => $gauntlet->getId()
            ]);
        }

        return $this->render('gauntlet/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/gauntlet/{id}", name="app_gauntlet_show")
     */
    public function show(Gauntlet $gauntlet)
    {
        return $this->render('gauntlet/show.html.twig', [
            'gauntlet' => $gauntlet
        ]);
    }
}
