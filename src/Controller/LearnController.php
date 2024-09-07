<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LearnController extends AbstractController
{
    #[Route('/berbere_latin', name: 'berbere_latin')]    
    /**
     * Apprentissage du berbère latin
     *
     * @param  mixed $request
     * @return Response
     */
    public function learnBerbereLatin(Request $request): Response
    {
        $locale = $request->getLocale(); // Get the current locale
        $template = $locale === 'en' ? 'learn/berbere_latin_en.html.twig' : 'learn/berbere_latin_fr.html.twig';

        return $this->render($template, [
            'controller_name' => 'LearnController',
        ]);
    }

    #[Route('/berbere_traditionnel', name: 'berbere_traditionnel')]    
    /**
     * Apprentissage du berbère traditionnel
     *
     * @param  mixed $request
     * @return Response
     */
    public function learnBerbereTraditionnel(Request $request): Response
    {
        $locale = $request->getLocale(); // Get the current locale
        $template = $locale === 'en' ? 'learn/berbere_traditionnel_en.html.twig' : 'learn/berbere_traditionnel_fr.html.twig';

        return $this->render($template, [
            'controller_name' => 'LearnController',
        ]);
    }

    #[Route('/grammaire', name: 'grammaire')]    
    /**
     * Apprentissage de la grammaire
     *
     * @param  mixed $request
     * @return Response
     */
    public function learnGrammaire(Request $request): Response
    {

        return $this->render('learn/grammaire.html.twig', [
            'controller_name' => 'LearnController',
        ]);
    }
}
