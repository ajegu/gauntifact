<?php

namespace App\Controller;

use App\Entity\Gauntlet;
use App\Form\GauntletType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/add-gauntlet", name="app_gauntlet_add")
     */
    public function add(Request $request)
    {
        $gauntlet = new Gauntlet();

        $form = $this->createForm(GauntletType::class, $gauntlet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deckCode = $form->get('deckCode')->getData();
        }

        return $this->render('gauntlet/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
