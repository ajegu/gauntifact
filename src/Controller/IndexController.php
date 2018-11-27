<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/", name="app_index")
     */
    public function index()
    {
        // On récupère les locales autorisées
        $locales = explode('|', $this->getParameter('app.locales'));

        // On récupère la locale de la requête HTTP
        $locale = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

        // On vérifie que la locale HTTP est connue
        if (!in_array($locale, $locales)) {
            // On redirige vers la locale par défaut de l'application
            $locale = $this->getParameter('kernel.default_locale');
        }

        return $this->redirectToRoute('app_home', [
            '_locale' => $locale
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{_locale}/", name="app_home", requirements={"_locale"="%app.locales%"})
     */
    public function home()
    {
        return $this->render('index/home.html.twig', [

        ]);
    }
}
