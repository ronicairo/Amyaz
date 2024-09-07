<?php

namespace App\Controller;

use DateTime;
use App\Form\StatusType;
use App\Form\AddWordType;
use App\Entity\Traduction;
use App\Repository\StatusRepository;
use App\Repository\TraductionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Validator\UniqueCombinationValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/traduction')]
class TraductionController extends AbstractController
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    #[Route('/', name: 'app_traduction_index', methods: ['GET'])]
    /**
     * Affiche la liste des traductions dans un tableau
     *
     * @param  mixed $traductionRepository
     * @param  mixed $request
     * @param  mixed $paginator
     * @return Response
     */
    public function index(TraductionRepository $traductionRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // Fetch translations
        $traductions = $traductionRepository->findAll();

        // Sort translations by wordFR
        usort($traductions, function (Traduction $a, Traduction $b) {
            return strcmp($a->getWordFR(), $b->getWordFR());
        });


        $pagination = $paginator->paginate(
            $traductions,
            $request->query->getInt('page', 1),
            20
        );
        // Render the template with sorted translations
        return $this->render('traduction/index.html.twig', [
            'traductions' => $traductions,
            'pagination' => $pagination
        ]);
    }

    #[Route('/new', name: 'app_traduction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security, StatusRepository $statusRepository, TraductionRepository $traductionRepository): Response
    {
        if (!$security->isGranted('ROLE_USER')) {
            $this->addFlash('error', $this->translator->trans('addflash.obligation_connexion'));
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();

        if (!$user->isVerified()) {
            $resendVerificationUrl = $this->generateUrl('app_resend_verification_email');

            $verificationMessage = $this->translator->trans('addflash.verification_compte', [
                '%resend_verification_url%' => $resendVerificationUrl
            ]);

            $this->addFlash('error', $verificationMessage);

            return $this->redirectToRoute('account');
        }

        $traduction = new Traduction();
        $form = $this->createForm(AddWordType::class, $traduction);
        $form->handleRequest($request);
        $locale = $this->translator->getLocale();

        if ($form->isSubmitted() && $form->isValid()) {
            $wordFR = ($traduction->getWordFR());
            $singular = ($traduction->getSingular());
            $wordEN = ($traduction->getWordEN());

            // Vérification de l'existence des mots dans la base de données
            $existingTraductionFR = $traductionRepository->findOneBy(['wordFR' => $wordFR]);
            $existingTraductionSingular = $traductionRepository->findOneBy(['singular' => $singular]);
            $existingTraductionEN = $traductionRepository->findOneBy(['wordEN' => $wordEN]);

            // Gestion des erreurs
            if ($existingTraductionFR !== null && $wordFR !== null && $existingTraductionFR->getWordFR() === $wordFR) {
                $this->addFlash('error', $this->translator->trans('addflash.traduction_already_exist', ['%mot%' => $wordFR]));
            } elseif ($existingTraductionSingular !== null && $singular !== null && $existingTraductionSingular->getSingular() === $singular) {
                $this->addFlash('error', $this->translator->trans('addflash.traduction_already_exist', ['%mot%' => $singular]));
            } elseif ($existingTraductionEN !== null && $wordEN !== null && $existingTraductionEN->getWordEN() === $wordEN) {
                $this->addFlash('error', $this->translator->trans('addflash.traduction_already_exist', ['%mot%' => $wordEN]));
            } else {
                $user = $this->getUser();
                $roles = $user->getRoles();
                $traduction->setRequestedBy($user);
                $traduction->setCreatedAt(new \DateTime());
                $traduction->setUpdatedAt(new \DateTime());

                $traduction->setRequest(true, $roles);

                if (!in_array('ROLE_MODERATOR', $roles) && !in_array('ROLE_ADMIN', $roles)) {
                    $pendingStatus = $statusRepository->findOneBy(['libelle' => 'pending']);
                    $traduction->setStatus($pendingStatus);
                } else {
                    $traduction->setStatus(null);
                }

                $entityManager->persist($traduction);
                $entityManager->flush();

                if (!in_array('ROLE_MODERATOR', $roles) && !in_array('ROLE_ADMIN', $roles)) {
                    $statutLink = $this->generateUrl('account_translations');
                    $attenteMotMessage = $this->translator->trans('addflash.attente_validation', [
                        '%status_url%' => $statutLink
                    ]);
                    $this->addFlash('success', $attenteMotMessage);
                } else {
                    $this->addFlash('success', $this->translator->trans('addflash.traduction_ajouter'));
                }

                return $this->redirectToRoute('show_home', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('traduction/new.html.twig', [
            'traduction' => $traduction,
            'form' => $form->createView(),
            'locale' => $locale,
        ]);
    }


    #[Route('/{id}/edit-status', name: 'app_traduction_edit_status', methods: ['GET', 'POST'])]
    /**
     * Permettre à l'administrateur ou modérateur de changer le statut d'une demande
     *
     * @param  mixed $request
     * @param  mixed $traduction
     * @param  mixed $entityManager
     * @return Response
     */
    public function editStatus(Request $request, Traduction $traduction, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MODERATOR')) {
            $this->addFlash('error', $this->translator->trans('addflash.restreindre_acces'));
            return $this->redirectToRoute('account');
        }

        // Créez un formulaire en utilisant le StatusType pour le champ 'status'
        $form = $this->createForm(StatusType::class, $traduction);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $traduction->setUpdatedAt(new DateTime());
            $entityManager->flush();

            $this->addFlash('success', "Le statut a bien été modifié.");
            return $this->redirectToRoute('account_pending_translations');
        }

        return $this->render('traduction/edit_status.html.twig', [
            'traduction' => $traduction,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_traduction_show', methods: ['GET'])]
    /**
     * Voir une traduction en détail
     *
     * @param  mixed $traduction
     * @return Response
     */
    public function show(Traduction $traduction): Response
    {
        return $this->render('traduction/show.html.twig', [
            'traduction' => $traduction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_traduction_edit', methods: ['GET', 'POST'])]
    /**
     * Modifier une traduction
     *
     * @param  mixed $request
     * @param  mixed $traduction
     * @param  mixed $entityManager
     * @return Response
     */
    public function edit(Request $request, Traduction $traduction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AddWordType::class, $traduction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', "Votre traduction a bien été modifiée.");
            return $this->redirectToRoute('app_traduction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('traduction/edit.html.twig', [
            'traduction' => $traduction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_traduction_delete', methods: ['POST'])]
    /**
     * Supprimer une trauction
     *
     * @param  mixed $request
     * @param  mixed $traduction
     * @param  mixed $entityManager
     * @return Response
     */
    public function delete(Request $request, Traduction $traduction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $traduction->getId(), $request->request->get('_token'))) {
            $entityManager->remove($traduction);
            $entityManager->flush();

            // Ajouter un message flash de succès
            $this->addFlash('success', $this->translator->trans('addflash.traduction_supprimer'));
        }

        // Redirection après suppression
        return $this->redirectToRoute('show_home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/search', name: 'search')]
    /**
     * Méthode pour la barre de recherche
     *
     * @param  mixed $request
     * @param  mixed $traductionRepository
     * @return Response
     */
    public function search(Request $request, TraductionRepository $traductionRepository): Response
    {
        // Récupérer le terme de recherche depuis la requête
        $searchTerm = $request->query->get('q');

        // Vérifier si le terme de recherche est fourni
        if ($searchTerm) {
            // Effectuer la recherche dans la base de données
            $results = $traductionRepository->search($searchTerm);

            // Retourner les résultats en tant que réponse JSON
            return new JsonResponse($results);
        }

        // Si aucun terme de recherche n'est fourni, retourner une réponse vide
        return new JsonResponse([]);
    }
}
