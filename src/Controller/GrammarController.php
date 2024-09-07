<?php

namespace App\Controller;

use App\Entity\GrammarSheet;
use App\Form\GrammarSheetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GrammarController extends AbstractController
{
    #[Route('/grammar/index', name: 'grammar_index', methods: ['GET'])]    
    /**
     * Voir les pages grammaires
     *
     * @param  mixed $entityManager
     * @param  mixed $request
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $locale = $request->getLocale(); // Get the current locale (e.g., 'fr' or 'en')
        $grammarSheets = $entityManager->getRepository(GrammarSheet::class)->findAll();

        // Process to extract only the first paragraph for the current locale
        foreach ($grammarSheets as $sheet) {
            // Determine which subtitle to use based on the locale
            $content = $locale === 'en' ? $sheet->getSubtitleEn() : $sheet->getSubtitleFr();

            $doc = new \DOMDocument();
            @$doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
            $paragraphs = $doc->getElementsByTagName('p');
            if ($paragraphs->length > 0) {
                $newSubtitle = $paragraphs->item(0)->nodeValue;

                // Set the subtitle based on locale
                if ($locale === 'en') {
                    $sheet->setSubtitleEn($newSubtitle);
                } else {
                    $sheet->setSubtitleFr($newSubtitle);
                }
            }
        }

        return $this->render('grammar/index.html.twig', [
            'grammarSheets' => $grammarSheets,
        ]);
    }

    #[Route('/grammaire', name: 'grammar', methods: ['GET'])]    
    /**
     * Voir les titres des pages grammaire de l'accueil
     *
     * @param  mixed $entityManager
     * @param  mixed $request
     * @return Response
     */
    public function grammar(EntityManagerInterface $entityManager, Request $request): Response
    {
        $repository = $entityManager->getRepository(GrammarSheet::class);
        $grammarSheets = $repository->findAll();

        // Determine the locale and use the appropriate fields
        $locale = $request->getLocale();
        $subtitleField = $locale === 'en' ? 'getSubtitleEn' : 'getSubtitleFr';
        $setSubtitleField = $locale === 'en' ? 'setSubtitleEn' : 'setSubtitleFr';
        $titleField = $locale === 'en' ? 'getTitleEn' : 'getTitleFr';

        // Process to extract only the first paragraph
        foreach ($grammarSheets as $sheet) {
            $content = $sheet->$subtitleField();
            $doc = new \DOMDocument();
            @$doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
            $paragraphs = $doc->getElementsByTagName('p');
            if ($paragraphs->length > 0) {
                $sheet->$setSubtitleField($paragraphs->item(0)->nodeValue);
            }
        }

        return $this->render('learn/grammaire.html.twig', [
            'grammarSheets' => $grammarSheets,
        ]);
    }

    #[Route('/grammarsheet/new', name: 'grammarsheet_new', methods: ['GET', 'POST'])]    
    /**
     * Créer des fiches grammaires
     *
     * @param  mixed $request
     * @param  mixed $entityManager
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $grammarSheet = new GrammarSheet();
        $form = $this->createForm(GrammarSheetType::class, $grammarSheet);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($grammarSheet);
            $entityManager->flush();

            $this->addFlash('success', 'Grammar sheet created successfully!');

            return $this->redirectToRoute('grammarsheet_show', ['id' => $grammarSheet->getId()]);
        }

        return $this->render('grammar/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/grammarsheet/{id}', name: 'grammarsheet_show', methods: ['GET'])]    
    /**
     * Voir le détail des fiches grammaires
     *
     * @param  mixed $grammarSheet
     * @return Response
     */
    public function show(GrammarSheet $grammarSheet): Response
    {
        return $this->render('grammar/show.html.twig', [
            'grammarSheet' => $grammarSheet,
        ]);
    }

    #[Route('/grammarsheet/{id}/edit', name: 'grammarsheet_edit', methods: ['GET', 'POST'])]    
    /**
     * Modifier une fiche grammaire
     *
     * @param  mixed $request
     * @param  mixed $grammarSheet
     * @param  mixed $entityManager
     * @return Response
     */
    public function edit(Request $request, GrammarSheet $grammarSheet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GrammarSheetType::class, $grammarSheet);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Grammar sheet updated successfully!');

            return $this->redirectToRoute('grammarsheet_show', ['id' => $grammarSheet->getId()]);
        }

        return $this->render('grammar/edit.html.twig', [
            'form' => $form->createView(),
            'grammarSheet' => $grammarSheet,
        ]);
    }

    #[Route('/grammarsheet/{id}/delete', name: 'grammarsheet_delete', methods: ['POST'])]    
    /**
     * Supprimer une fiche grammaire
     *
     * @param  mixed $request
     * @param  mixed $grammarSheet
     * @param  mixed $entityManager
     * @return Response
     */
    public function delete(Request $request, GrammarSheet $grammarSheet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $grammarSheet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($grammarSheet);
            $entityManager->flush();

            $this->addFlash('success', 'La fiche grammaire a bien été supprimée.');
        }

        return $this->redirectToRoute('grammar_index');
}
}