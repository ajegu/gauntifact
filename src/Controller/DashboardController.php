<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Gauntlet;
use App\Entity\GauntletType;
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/dashboard", name="app_dashboard")
     */
    public function index(Request $request)
    {
        $startDate = new \DateTime();
        $endDate = new \DateTime();
        $gauntletTypeId = $request->get('gauntletType');

        if ($request->get('startDate') !== null) {
            $startDate = new \DateTime($request->get('startDate'));
        }

        if ($request->get('endDate') !== null) {
            $endDate = new \DateTime($request->get('endDate'));
        }

        $gauntletType = null;
        if ($gauntletTypeId !== null) {
            $gauntletType = $this->getDoctrine()->getRepository(GauntletType::class)
                ->find($gauntletTypeId);
        }

        $stats = $this->calculerStats($startDate, $endDate, $gauntletType);

        $gauntletTypes = $this->getDoctrine()->getRepository(GauntletType::class)
            ->findAll();

        return $this->render('dashboard/index.html.twig', [
            'stats' => $stats,
            'gauntletTypes' => $gauntletTypes
        ]);
    }

    /**
     * @param \DateTime|null $startDate
     * @param \DateTime|null $endDate
     * @param GauntletType|null $gauntletType
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function calculerStats(\DateTime $startDate = null, \DateTime $endDate = null, GauntletType $gauntletType = null)
    {
        $startDate = $startDate === null ? new \DateTime() : $startDate;
        $endDate = $endDate === null ? new \DateTime() : $endDate;

        $totalGauntlets = $this->getDoctrine()->getRepository(Gauntlet::class)
            ->countGauntletByDates($this->getUser(), $startDate, $endDate, $gauntletType);

        $totalGames = $this->getDoctrine()->getRepository(Game::class)
            ->countGamesByDates($this->getUser(), $startDate, $endDate, null, $gauntletType);

        $totalGamesWon = $this->getDoctrine()->getRepository(Game::class)
            ->countGamesByDates($this->getUser(), $startDate, $endDate, [Game::STATUS_WIN], $gauntletType);

        $totalGamesLost = $this->getDoctrine()->getRepository(Game::class)
            ->countGamesByDates($this->getUser(), $startDate, $endDate, [Game::STATUS_DRAW, Game::STATUS_LOSE], $gauntletType);

        return [
            'totalGauntlets' => $totalGauntlets,
            'totalGames' => $totalGames,
            'totalGamesWon' => $totalGamesWon,
            'totalGamesLost' => $totalGamesLost
        ];
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @Route("/dashboard-stats", name="app_dashboard_stats")
     */
//    public function stats(Request $request)
//    {
//        $startDate = new \DateTime();
//        $endDate = new \DateTime();
//
//        if ($request->get('startDate') !== null) {
//            $startDate = new \DateTime($request->get('startDate'));
//        }
//
//        if ($request->get('endDate') !== null) {
//            $endDate = new \DateTime($request->get('endDate'));
//        }
//
//        $stats = $this->calculerStats($startDate, $endDate);
//
//        return $this->render('dashboard/stats.html.twig', [
//            'totalGauntlets' => $stats['totalGauntlets'],
//            'totalGames' => $stats['totalGames'],
//            'totalGamesWon' => $stats['totalGamesWon'],
//            'totalGamesLost' => $stats['totalGamesLost'],
//        ]);
//    }
}
