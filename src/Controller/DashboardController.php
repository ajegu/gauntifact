<?php

namespace App\Controller;

use App\Service\GauntletService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/dashboard", name="app_dashboard")
     */
    public function index(GauntletService $gauntletService)
    {
        return $this->render('dashboard/index.html.twig');
    }
}
