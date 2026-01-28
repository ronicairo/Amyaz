<?php

namespace App\Controller;

use DateTime;
use App\Entity\Verbe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VerbeController extends AbstractController
{
    private TranslatorInterface $translator;
    private EntityManagerInterface $entityManager;

    public function __construct(TranslatorInterface $translator, EntityManagerInterface $entityManager)
    {
        $this->translator = $translator;
        $this->entityManager = $entityManager;
    }

    #[Route('/show_form_verb', name: 'show_form_verb', methods: ['GET'])]
    public function showFormVerb(): Response
    {
        // Vérifier les rôles de l'utilisateur
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MODERATOR')) {
            $this->addFlash('danger', $this->translator->trans('addflash.restreindre_acces'));
            return $this->redirectToRoute('account');
        }
        return $this->render('conjugation/add_verb.html.twig');
    }

    #[Route('/add_verb', name: 'add_verb', methods: ['POST'])]
    public function addVerb(Request $request): Response
    {
        // Vérifier les rôles de l'utilisateur
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MODERATOR')) {
            $this->addFlash('danger', $this->translator->trans('addflash.restreindre_acces'));
            return $this->redirectToRoute('account');
        }

        // Créer une nouvelle instance de l'entité Verbe
        $verbe = new Verbe();
        $verbe->setVerbeRifain($request->request->get('verbe_rifain'));
        $verbe->setVerbeFrancais($request->request->get('verbe_francais'));
        $verbe->setVerbeTifinagh($request->request->get('verbe_tifinagh'));
        $verbe->setForme($request->request->get('forme'));
        $verbe->setR1($request->request->get('R1'));
        $verbe->setR2($request->request->get('R2'));
        $verbe->setR3($request->request->get('R3'));
        $verbe->setR4($request->request->get('R4'));
        $verbe->setR5($request->request->get('R5'));
        $verbe->setR6($request->request->get('R6'));
        $verbe->setR7($request->request->get('R7'));
        $verbe->setCreatedAt(new DateTime());
        $verbe->setUpdatedAt(new DateTime());

        // Sauvegarder le verbe dans la base de données
        $this->entityManager->persist($verbe);
        $this->entityManager->flush();

        // Ajouter un message flash et rediriger l'utilisateur
        $this->addFlash('success', "Le verbe a bien été ajouté.");
        return $this->redirectToRoute('app_conjugation');
    }

    #[Route('/edit_verb/{id}', name: 'edit_verb', methods: ['POST', 'GET'])]
    public function editVerb(Request $request, Verbe $verbe): Response
    {
        // Vérifier les rôles de l'utilisateur
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MODERATOR')) {
            $this->addFlash('danger', $this->translator->trans('addflash.restreindre_acces'));
            return $this->redirectToRoute('account');
        }
    
        // Si la méthode est GET, on affiche le formulaire
        if ($request->isMethod('GET')) {
            return $this->render('conjugation/edit_verb.html.twig', [
                'verbe' => $verbe,
            ]);
        }
    
        // Mise à jour des champs du verbe
        $verbe->setVerbeRifain($request->request->get('verbe_rifain'));
        $verbe->setVerbeFrancais($request->request->get('verbe_francais'));
        $verbe->setVerbeTifinagh($request->request->get('verbe_tifinagh'));
        $verbe->setForme($request->request->get('forme'));
        $verbe->setR1($request->request->get('R1'));
        $verbe->setR2($request->request->get('R2'));
        $verbe->setR3($request->request->get('R3'));
        $verbe->setR4($request->request->get('R4'));
        $verbe->setR5($request->request->get('R5'));
        $verbe->setR6($request->request->get('R6'));
        $verbe->setR7($request->request->get('R7'));
        $verbe->setUpdatedAt(new DateTime());
    
        // Enregistrer les modifications dans la base de données
        $this->entityManager->flush();
    
        $this->addFlash('success', "Le verbe a bien été modifié.");
        return $this->redirectToRoute('app_conjugation');
    }
    

    #[Route('/delete_verb/{id}', name: 'delete_verb', methods: ['POST'])]
    public function deleteVerb(Verbe $verbe): Response
    {
        // Vérifier les rôles de l'utilisateur
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MODERATOR')) {
            $this->addFlash('danger', $this->translator->trans('addflash.restreindre_acces'));
            return $this->redirectToRoute('account');
        }

        // Supprimer le verbe de la base de données
        $this->entityManager->remove($verbe);
        $this->entityManager->flush();

        $this->addFlash('success', "Le verbe a bien été supprimé.");
        return $this->redirectToRoute('app_conjugation');
    }

    
}