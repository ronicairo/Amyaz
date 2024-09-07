<?php

namespace App\Controller;

use DateTime;
use App\Entity\CategoryDoc;
use App\Form\CategoryDocType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryDocRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/category/doc')]
class CategoryDocController extends AbstractController
{
    #[Route('/', name: 'category_doc_index', methods: ['GET'])]    
    public function index(CategoryDocRepository $categoryDocRepository): Response
    {
        return $this->render('category_doc/index.html.twig', [
            'category_docs' => $categoryDocRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'category_doc_new', methods: ['GET', 'POST'])]    
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categoryDoc = new CategoryDoc();
        $form = $this->createForm(CategoryDocType::class, $categoryDoc);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryDoc->setCreatedAt(new DateTime());
            $categoryDoc->setUpdatedAt(new DateTime());
            $entityManager->persist($categoryDoc);
            $entityManager->flush();

            $this->addFlash('success', "Votre catégorie a bien été crée.");
            return $this->redirectToRoute('category_doc_index');
        }

        return $this->render('category_doc/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'category_doc_show', methods: ['GET'])]    
    public function show(CategoryDoc $categoryDoc): Response
    {
        return $this->render('category_doc/show.html.twig', [
            'category_doc' => $categoryDoc,
        ]);
    }

    #[Route('/{id}/edit', name: 'category_doc_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategoryDoc $categoryDoc, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(CategoryDocType::class, $categoryDoc);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // $categoryDoc->setUpdatedAt(new \DateTime());
            $entityManager->flush();
        
            $this->addFlash('success', "Votre catégorie a bien été modifiée.");
            return $this->redirectToRoute('category_doc_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('category_doc/edit.html.twig', [
            'form' => $form->createView(),
            'category_doc' => $categoryDoc,
        ]);
    }
    

    #[Route('/{id}', name: 'category_doc_delete', methods: ['POST'])]    
    public function delete(Request $request, CategoryDoc $categoryDoc, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoryDoc->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categoryDoc);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_doc_index', [], Response::HTTP_SEE_OTHER);
    }
}
