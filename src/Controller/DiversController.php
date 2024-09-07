<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DiversController extends AbstractController
{
    #[Route('/mentions-legales', name: 'mentions_legales',methods:['GET'])]    
    /**
     * Mentions légales
     *
     * @param  mixed $request
     * @return Response
     */
    public function mentionsLegal(Request $request): Response
    {
        $locale = $request->getLocale(); // Get the current locale
        $template = $locale === 'en' ? 'footer/mentions_legales_en.html.twig' : 'footer/mentions_legales_fr.html.twig';

        return $this->render($template);
    }
    

    #[Route('/a_propos', name: 'a_propos',methods:['GET'])]    
    /**
     * A Propos
     *
     * @param  mixed $request
     * @return Response
     */
    public function aPropos(Request $request): Response
    {
        $locale = $request->getLocale(); // Get the current locale
        $template = $locale === 'en' ? 'footer/a_propos_en.html.twig' : 'footer/a_propos_fr.html.twig';

        return $this->render($template);
    }
    

    #[Route('/cookie', name: 'cookie',methods:['GET'])]    
    /**
     * Cookie
     *
     * @param  mixed $request
     * @return Response
     */
    public function cookie(Request $request): Response
    {
        $locale = $request->getLocale(); // Get the current locale
        $template = $locale === 'en' ? 'footer/cookie_en.html.twig' : 'footer/cookie_fr.html.twig';

        return $this->render($template);
    }
    
}
