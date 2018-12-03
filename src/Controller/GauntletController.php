<?php

namespace App\Controller;

use App\Entity\Gauntlet;
use App\Exception\GauntletNotNullException;
use App\Form\GauntletType;
use App\Service\DeckService;
use App\Service\GauntletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class GauntletController extends AbstractController
{
    /**
     * @param GauntletService $gauntletService
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/gauntlet-list", name="app_gauntlet_list")
     */
    public function list(GauntletService $gauntletService)
    {
        $gauntlets = $gauntletService->list($this->getUser());

        return $this->render('gauntlet/list.html.twig', [
            'gauntlets' => $gauntlets,
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
     * @param Gauntlet $gauntlet
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/gauntlet/{id}", name="app_gauntlet_show")
     */
    public function show(Gauntlet $gauntlet)
    {
        return $this->render('gauntlet/show.html.twig', [
            'gauntlet' => $gauntlet
        ]);
    }


    /**
     * @param Gauntlet $gauntlet
     * @param GauntletService $gauntletService
     * @param TranslatorInterface $translator
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/unlock-gauntlet/{id}", name="app_gauntlet_unlock")
     */
    public function unlock(Gauntlet $gauntlet, GauntletService $gauntletService, TranslatorInterface $translator)
    {
        $response = [
            'success' => true,
            'message' => $translator->trans('success.gauntlet_unlock')
        ];

        try {
            $gauntletService->unlock($gauntlet);
        } catch (GauntletNotNullException $e) {
            return $this->render('gauntlet/unlock.html.twig');
        }

        return new JsonResponse($response);
    }
}
