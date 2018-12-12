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

        $stats = $this->calculerStats($request->getLocale(), $startDate, $endDate, $gauntletType);

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
     * @param string $locale
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function calculerStats(string $locale, \DateTime $startDate = null, \DateTime $endDate = null, GauntletType $gauntletType = null)
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

        $games = $this->getDoctrine()->getRepository(Game::class)
            ->getGamesByDates($this->getUser(), $startDate, $endDate, null, $gauntletType);


        $int = $startDate->diff($endDate);
        $days = (int) $int->format('%a');

        if ($days === 0) {
            $chartGames = $this->generateChartForHours($games);
        } else if ($days > 0 && $days < 31) {
            $chartGames = $this->generateChartForDays($games, $startDate, $endDate, $locale);
        } else {
            $chartGames = $this->generateChartForMonths($games, $startDate, $endDate, $locale);
        }

        return [
            'totalGauntlets' => $totalGauntlets,
            'totalGames' => $totalGames,
            'totalGamesWon' => $totalGamesWon,
            'totalGamesLost' => $totalGamesLost,
            'chartGames' => $chartGames
        ];
    }

    /**
     * @param array $games
     * @return array
     */
    private function generateChartForHours(array $games): array
    {
        $chartGames = [];
        for ($i = 0; $i < 24; $i++) {
            $ind = str_pad($i, 2, '0', STR_PAD_LEFT);
            $chartGames[$ind] = [
                'total' => 0,
                'win' => 0,
                'label' => $ind . 'h'
            ];
        }

        foreach ($games as $game) {
            $ind = $game->getPlayedAt()->format('H');
            $chartGames[$ind]['total']++;
            if ($game->getStatus() === Game::STATUS_WIN) {
                $chartGames[$ind]['win']++;
            }
        }

        return $chartGames;
    }

    /**
     * @param Game[] $games
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param string $locale
     * @return array
     * @throws \Exception
     */
    private function generateChartForDays(array $games, \DateTime $startDate, \DateTime $endDate, string $locale): array
    {
        $dateFormatter = new \IntlDateFormatter($locale, \IntlDateFormatter::SHORT, \IntlDateFormatter::NONE);

        $chartGames = [];
        $currentDate = $startDate;
        do {
            // On récupère le nombre de jour entre la date courante et la date de fin
            $int = $currentDate->diff($endDate);
            $days = $int->format('%a');

            $label = $dateFormatter->format($currentDate);
            $chartGames[$label] = [
                'label' => $label,
                'total' => 0,
                'win' => 0
            ];

            // On incrémente la date courante
            $int = new \DateInterval('P1D');
            $currentDate->add($int);
        } while ($days > 0 && $currentDate <= $endDate);

        foreach ($games as $game) {
            $ind = $dateFormatter->format($game->getPlayedAt());
            $chartGames[$ind]['total']++;
            if ($game->getStatus() === Game::STATUS_WIN) {
                $chartGames[$ind]['win']++;
            }
        }

        return $chartGames;
    }

    /**
     * @param Game[] $games
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param string $locale
     * @return array
     * @throws \Exception
     */
    private function generateChartForMonths(array $games, \DateTime $startDate, \DateTime $endDate, string $locale): array
    {
        $dateFormatter = new \IntlDateFormatter($locale, \IntlDateFormatter::SHORT, \IntlDateFormatter::NONE);
        $dateFormatter->setPattern('MMMM');

        $chartGames = [];
        $currentDate = $startDate;

        do {
            // On récupère le nombre de jour entre la date courante et la date de fin
            $int = $currentDate->diff($endDate);
            $days = $int->format('%a');

            $label = ucfirst($dateFormatter->format($currentDate));
            $chartGames[$label] = [
                'label' => $label,
                'total' => 0,
                'win' => 0
            ];

            // On incrémente la date courante
            $int = new \DateInterval('P1M');
            $currentDate->add($int);
        } while ($days > 0 && $currentDate <= $endDate);

        foreach ($games as $game) {
            $ind = ucfirst($dateFormatter->format($game->getPlayedAt()));
            $chartGames[$ind]['total']++;
            if ($game->getStatus() === Game::STATUS_WIN) {
                $chartGames[$ind]['win']++;
            }
        }

        return $chartGames;
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
