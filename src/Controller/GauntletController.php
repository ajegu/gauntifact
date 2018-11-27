<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
