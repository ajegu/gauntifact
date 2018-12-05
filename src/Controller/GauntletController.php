<?php

namespace App\Controller;

use App\Entity\Gauntlet;
use App\Exception\GauntletNotNullException;
use App\Form\GauntletType;
use App\Service\DeckService;
use App\Service\GauntletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class GauntletController extends AbstractController
{
    /**
     * @param Request $request
     * @param GauntletService $gauntletService
     * @param TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/gauntlet-list", name="app_gauntlet_list")
     */
    public function list(Request $request, GauntletService $gauntletService, TranslatorInterface $translator)
    {
        $draw = $request->get('draw', 1);
        $length = $request->get('length', 10);
        $start = $request->get('start', 0);
        $order = $request->get('order', [
            "column" => "1",
            "dir" => "desc"
        ]);

        $columnsMapping = [
            '0' => 'number',
            '1' => 'playedAt',
            '2' => 'type',
            '3' => 'status'
        ];

        $orderName = $columnsMapping[$order[0]['column']];
        $orderDir = $order[0]['dir'];

        $dateFormatter = new \IntlDateFormatter($request->getLocale(), \IntlDateFormatter::SHORT, \IntlDateFormatter::SHORT);

        $total = $gauntletService->getTotalGauntlets($this->getUser());
        $gauntlets = $gauntletService->list($this->getUser(), $start, $length, $orderName, $orderDir);

        $result = [
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
        ];

        foreach ($gauntlets as $gauntlet) {
            $result['data'][] = [
                'id' => $gauntlet->getId(),
                'number' => $gauntlet->getNumber(),
                'playedAt' => $dateFormatter->format($gauntlet->getPlayedAt()),
                'type' => $gauntlet->getType()->getName(),
                'status' => $gauntlet->getStatus(),
                'actions' => $translator->trans('btn.show')
            ];
        }

        return new JsonResponse($result);
    }

    /**
     * @Route("/gauntlet-show-history", name="app_gauntlet_show_history")
     */
    public function showHistory()
    {
        return $this->render('gauntlet/show_history.html.twig');
    }

    /**
     * @param Request $request
     * @param DeckService $deckService
     * @param GauntletService $gauntletService
     * @param TranslatorInterface $translator
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \App\Exception\CardNotFoundException
     * @throws \App\Exception\GauntletNotNullException
     *
     * @Route("/add-gauntlet", name="app_gauntlet_add")
     */
    public function add(Request $request, DeckService $deckService, GauntletService $gauntletService, TranslatorInterface $translator)
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

            $this->addFlash(
                'success',
                $translator->trans('success.add_gauntlet')
            );

            return new JsonResponse([
                'success' => true,
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
        try {
            $gauntletService->unlock($gauntlet);
        } catch (GauntletNotNullException $e) {
            return $this->render('gauntlet/unlock.html.twig');
        }

        $this->addFlash(
            'success',
            $translator->trans('success.unlock_gauntlet')
        );

        return new JsonResponse([
            'success' => true
        ]);
    }

    /**
     * @param Gauntlet $gauntlet
     * @param GauntletService $gauntletService
     * @param TranslatorInterface $translator
     * @return JsonResponse
     * @throws \App\Exception\GauntletLockException
     *
     * @Route("/lock-gauntlet/{id}", name="app_gauntlet_lock")
     */
    public function lock(Gauntlet $gauntlet, GauntletService $gauntletService, TranslatorInterface $translator)
    {
        $gauntletService->lock($gauntlet);

        $this->addFlash(
            'success',
            $translator->trans('success.lock_gauntlet')
        );

        return new JsonResponse([
            'success' => true
        ]);
    }

    /**
     * @param Gauntlet $gauntlet
     * @param GauntletService $gauntletService
     * @param TranslatorInterface $translator
     * @return JsonResponse
     * @throws \App\Exception\GauntletStatusException
     *
     * @Route("/concede-gauntlet/{id}", name="app_gauntlet_concede")
     */
    public function concede(Gauntlet $gauntlet, GauntletService $gauntletService, TranslatorInterface $translator)
    {
        $gauntletService->concede($gauntlet);

        $this->addFlash(
            'success',
            $translator->trans('success.concede_gauntlet')
        );

        return new JsonResponse([
            'success' => true
        ]);
    }


    /**
     * @param Request $request
     * @param Gauntlet $gauntlet
     * @param DeckService $deckService
     * @param GauntletService $gauntletService
     * @param TranslatorInterface $translator
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws GauntletNotNullException
     * @throws \App\Exception\CardNotFoundException
     * @throws \App\Exception\GauntletStatusException
     *
     * @Route("/edit-gauntlet/{id}", name="app_gauntlet_edit")
     */
    public function edit(Request $request, Gauntlet $gauntlet, DeckService $deckService, GauntletService $gauntletService, TranslatorInterface $translator)
    {
        $form = $this->createForm(GauntletType::class, $gauntlet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deckCode = $form->get('deckCode')->getData();

            if ($deckCode !== $gauntlet->getDeck()->getCode()) {
                $deck = $deckService->createDeckFromCode($deckCode);
                $gauntletService->changeDeck($gauntlet, $deck);
            }

            $gauntletService->edit($gauntlet);

            $this->addFlash(
                'success',
                $translator->trans('success.edit_gauntlet')
            );

            return new JsonResponse([
                'success' => true
            ]);
        }

        return $this->render('gauntlet/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Gauntlet $gauntlet
     * @param GauntletService $gauntletService
     * @param TranslatorInterface $translator
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/delete-gauntlet/{id}", name="app_gauntlet_delete")
     */
    public function delete(Request $request, Gauntlet $gauntlet, GauntletService $gauntletService, TranslatorInterface $translator)
    {
        $form = $this->createForm(FormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $gauntletService->delete($gauntlet);

            $this->addFlash(
                'success',
                $translator->trans('success.delete_gauntlet')
            );

            return new JsonResponse([
                'success' => true
            ]);
        }

        return $this->render('gauntlet/delete.html.twig', [
            'form' => $form->createView(),
            'gauntlet' => $gauntlet
        ]);
    }
}
