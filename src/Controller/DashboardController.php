<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Gauntlet;
use App\Service\GauntletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @param GauntletService $gauntletService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function menu(GauntletService $gauntletService)
    {
        // On check qu'un affrontement "en cours" existe
        $gauntlet = $gauntletService->getCurrent($this->getUser());

        return $this->render('dashboard/menu.html.twig', [
            'currentGauntlet' => $gauntlet
        ]);
    }


    /**
     * @param Request $request
     * @param GauntletService $gauntletService
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @Route("/dashboard", name="app_dashboard")
     */
    public function index(Request $request, GauntletService $gauntletService)
    {
        $startDate = new \DateTime();
        $endDate = new \DateTime();

        $totalGauntlets = $this->getDoctrine()->getRepository(Gauntlet::class)
            ->countGauntletByDates($this->getUser(), $startDate, $endDate);

        $totalGames = $this->getDoctrine()->getRepository(Game::class)
            ->countGamesByDates($this->getUser(), $startDate, $endDate);

        $totalGamesWon = $this->getDoctrine()->getRepository(Game::class)
            ->countGamesByDates($this->getUser(), $startDate, $endDate, [Game::STATUS_WIN]);

        $totalGamesLost = $this->getDoctrine()->getRepository(Game::class)
            ->countGamesByDates($this->getUser(), $startDate, $endDate, [Game::STATUS_DRAW, Game::STATUS_LOSE]);

        return $this->render('dashboard/index.html.twig', [
            'totalGauntlets' => $totalGauntlets,
            'totalGames' => $totalGames,
            'totalGamesWon' => $totalGamesWon,
            'totalGamesLost' => $totalGamesLost,
        ]);
    }

    /**
     * @param Request $request
     * @param GauntletService $gauntletService
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @Route("/dashboard-stats", name="app_dashboard_stats")
     */
    public function stats(Request $request, GauntletService $gauntletService)
    {
        $startDate = new \DateTime();
        $endDate = new \DateTime();

        if ($request->get('startDate') !== null) {
            $startDate = new \DateTime($request->get('startDate'));
        }

        if ($request->get('endDate') !== null) {
            $endDate = new \DateTime($request->get('endDate'));
        }

        $totalGauntlets = $this->getDoctrine()->getRepository(Gauntlet::class)
            ->countGauntletByDates($this->getUser(), $startDate, $endDate);

        $totalGames = $this->getDoctrine()->getRepository(Game::class)
            ->countGamesByDates($this->getUser(), $startDate, $endDate);

        $totalGamesWon = $this->getDoctrine()->getRepository(Game::class)
            ->countGamesByDates($this->getUser(), $startDate, $endDate, [Game::STATUS_WIN]);

        $totalGamesLost = $this->getDoctrine()->getRepository(Game::class)
            ->countGamesByDates($this->getUser(), $startDate, $endDate, [Game::STATUS_DRAW, Game::STATUS_LOSE]);

        return $this->render('dashboard/stats.html.twig', [
            'totalGauntlets' => $totalGauntlets,
            'totalGames' => $totalGames,
            'totalGamesWon' => $totalGamesWon,
            'totalGamesLost' => $totalGamesLost,
        ]);
    }
}
