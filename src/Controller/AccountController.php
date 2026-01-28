<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Favorite;
use App\Form\ChangeEmailFormType;
use App\Repository\UserRepository;
use App\Form\ChangePasswordFormType;
use App\Repository\FavoriteRepository;
use App\Repository\TraductionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{

    private TranslatorInterface $translator;
    private Security $security;
    public function __construct(TranslatorInterface $translator, Security $security)
    {
        $this->translator = $translator;
        $this->security = $security;
    }

    #[Route('/account', name: 'account')]
    /**
     * Page profil
     *
     * @return Response
     */
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('account/account.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/dashboard', name: 'dashboard')]
    /**
     * Tableau de bord
     *
     * @return Response
     */
    public function dashboard(): Response
    {
        $user = $this->getUser();

        return $this->render('account/dashboard.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/account/edit_username{id}', name: 'update_username', methods: ['GET', 'POST'])]
    /**
     * Modifier son nom
     *
     * @param  mixed $user
     * @param  mixed $request
     * @param  mixed $repository
     * @param  mixed $entityManager
     * @return Response
     */
    public function updateUsername(User $user, Request $request, UserRepository $repository, EntityManagerInterface $entityManager): Response
    {
        try {
            $this->denyAccessUnlessGranted("ROLE_USER");
        } catch (AccessDeniedException $exception) {
            $this->addFlash('danger', $this->translator->trans('addflash.connexion_acces'));
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, [
                'label' => $this->translator->trans('form.new_username')
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($user, true);

            $this->addFlash('success', $this->translator->trans('addflash.username_update_success'));
            return $this->redirectToRoute('account');
        }

        return $this->render('account/update/update_username_form.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/account/edit_password{id}', name: 'update_password', methods: ['GET', 'POST'])]
    /**
     * Modifier son mot de passe
     *
     * @param  mixed $user
     * @param  mixed $request
     * @param  mixed $repository
     * @param  mixed $slugger
     * @param  mixed $hasher
     * @return Response
     */
    public function updatePassword(User $user, Request $request, UserRepository $repository, SluggerInterface $slugger, UserPasswordHasherInterface $hasher): Response
    {
        try {
            $this->denyAccessUnlessGranted("ROLE_USER");
        } catch (AccessDeniedException) {
            $this->addFlash('danger', $this->translator->trans('addflash.connexion_acces'));
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ChangePasswordFormType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $repository->find($this->getUser());

            $currentPassword = $form->get('currentPassword')->getData();

            if (!$hasher->isPasswordValid($user, $currentPassword)) {
                $this->addFlash('warning', $this->translator->trans('addflash.password_echec'));
                return $this->redirectToRoute('update_password');
            }

            $user->setUpdatedAt(new DateTime());

            $plainPassword = $form->get('password')->getData();

            $user->setPassword($hasher->hashPassword($user, $plainPassword));

            $repository->save($user, true);

            $this->addFlash('success',  $this->translator->trans('addflash.password_success'));
            return $this->redirectToRoute('account');
        }

        return $this->render('account/update/update_password_form.html.twig', [
            'form' => $form->createView()
        ]);
    } // end updatePassword()

    #[Route('/account/edit_email{id}', name: 'update_email', methods: ['GET', 'POST'])]
    /**
     * Modifier son email
     *
     * @param  mixed $user
     * @param  mixed $request
     * @param  mixed $repository
     * @param  mixed $entityManager
     * @return Response
     */
    public function updateEmail(User $user, Request $request, UserRepository $repository, EntityManagerInterface $entityManager): Response
    {
        try {
            $this->denyAccessUnlessGranted("ROLE_USER");
        } catch (AccessDeniedException $exception) {
            $this->addFlash('danger', $this->translator->trans('addflash.connexion_acces'));
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ChangeEmailFormType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainEmail = $form->get('plainEmail')->getData();
            $repeatEmail = $form->get('repeatEmail')->getData();

            if ($plainEmail !== $repeatEmail) {
                $this->addFlash('danger', $this->translator->trans('addflash.email_not_same'));
                return $this->redirectToRoute('update_email', ['id' => $user->getId()]);
            }

            // Check if the email is already used by another user
            $existingUser = $repository->findOneBy(['email' => $plainEmail]);
            if ($existingUser && $existingUser->getId() !== $user->getId()) {
                $this->addFlash('danger', $this->translator->trans('addflash.email_already_use'));
                return $this->redirectToRoute('update_email', ['id' => $user->getId()]);
            }

            $user->setEmail($plainEmail);

            $repository->save($user);
            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('addflash.email_update_success'));
            return $this->redirectToRoute('account');
        }

        return $this->render('account/update/update_email_form.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/account/edit_picture{id}', name: 'update_picture', methods: ['GET', 'POST'])]
    /**
     * Modifier sa photo de profil
     *
     * @param  mixed $user
     * @param  mixed $request
     * @param  mixed $entityManager
     * @param  mixed $slugger
     * @return Response
     */
    public function updatePicture(User $user, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        try {
            $this->denyAccessUnlessGranted("ROLE_USER");
        } catch (AccessDeniedException $exception) {
            $this->addFlash('danger', $this->translator->trans('addflash.connexion_acces'));
            return $this->redirectToRoute('app_login');
        }

        // Vérifiez si la requête est une soumission de formulaire POST
        if ($request->isMethod('POST')) {
            // Récupérer le fichier de la requête
            $pictureFile = $request->files->get('picture');

            // Manipuler le fichier pour télécharger et enregistrer la nouvelle photo de profil
            if ($pictureFile instanceof UploadedFile) {
                // Gérer le fichier et obtenir le statut de succès ou d'erreur
                $fileHandlingResult = $this->handleFile($user, $pictureFile, $slugger);

                // Si le traitement du fichier a réussi
                if ($fileHandlingResult['success']) {
                    // Enregistrez les modifications dans la base de données
                    $entityManager->flush();

                    $this->addFlash('success', $this->translator->trans('addflash.picture_update_success'));
                } else {
                    // Si le traitement du fichier a échoué, ajoutez le message d'erreur approprié
                    $this->addFlash('danger', $fileHandlingResult['message']);
                }

                return $this->redirectToRoute('account');
            }
        }

        return $this->redirectToRoute('account');
    }

    private function handleFile(User $user, UploadedFile $pictureFile, SluggerInterface $slugger)
    {
        $newFilename = '';
        $result = ['success' => false, 'message' => ''];

        // Vérifier si un fichier a été téléchargé
        if ($pictureFile instanceof UploadedFile) {
            // Ajouter une validation pour vérifier le type de fichier
            $allowedExtensions = ['png', 'jpg', 'jpeg'];
            $fileExtension = strtolower($pictureFile->getClientOriginalExtension());

            if (!in_array($fileExtension, $allowedExtensions)) {
                $result['message'] = "Veuillez télécharger un fichier PNG ou JPG pour votre photo de profil.";
                return $result;
            }

            $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $fileExtension;

            // Déplacer le fichier téléchargé vers le répertoire de destination
            try {
                $pictureFile->move(
                    $this->getParameter('profile_directory'),
                    $newFilename
                );

                // Mettre à jour le chemin de la photo de profil dans l'entité User
                $user->setPicture($newFilename);

                // Indiquer que le traitement du fichier s'est déroulé avec succès
                $result['success'] = true;
            } catch (FileException $e) {
                // Gérer les erreurs lors du téléchargement du fichier
                $result['message'] = "Une erreur s'est produite lors du téléchargement de la photo de profil. Veuillez réessayer.";
            }
        }

        return $result;
    }

    #[Route('/account/translations', name: 'account_translations')]
    /**
     * Voir les traductions des utilisateurs
     *
     * @param  mixed $traductionRepository
     * @return Response
     */
    public function userTranslations(TraductionRepository $traductionRepository): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer les traductions effectuées par cet utilisateur
        $posts = $traductionRepository->findBy(['requestedBy' => $user]);

        return $this->render('account/post.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/account/pending-translations', name: 'account_pending_translations')]
    /**
     * Traduction en attente
     *
     * @param  mixed $traductionRepository
     * @return Response
     */
    public function pendingTranslations(TraductionRepository $traductionRepository): Response
    {

        // Vérifier les rôles de l'utilisateur
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MODERATOR')) {
            $this->addFlash('danger', $this->translator->trans('addflash.restreindre_acces'));
            return $this->redirectToRoute('account');
        }

        // Récupérer les traductions avec le statut 'pending' (statut = 1)
        $pendingTranslations = $traductionRepository->findBy(['status' => 1]);

        return $this->render('account/pending_translations.html.twig', [
            'pendingTranslations' => $pendingTranslations,
        ]);
    }

    #[Route('/favorites', name: 'show_favorites')]
    public function showFavorites(Request $request, FavoriteRepository $favoriteRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException($this->translator->trans('home.fav_login'));
        }

        $queryBuilder = $favoriteRepository->createQueryBuilder('f')
            ->where('f.user = :user')
            ->setParameter('user', $user);

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1), // Numéro de la page
            10 // Limite par page
        );


        return $this->render('account/favorites.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/favorite/remove', name: 'fav_remove', methods: ['POST'])]
    public function removeFav(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->security->getUser();
        if (!$user) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $this->translator->trans('favorite_remove_login_required')
            ], 401);
        }

        $data = json_decode($request->getContent(), true);
        $favoriteId = $data['id'];
        $type = $data['type']; // Expecting 'word' or 'doc'

        $favorite = $em->getRepository(Favorite::class)->findOneBy([
            'user' => $user,
            $type === 'word' ? 'traduction' : 'documentation' => $favoriteId
        ]);

        if (!$favorite) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $this->translator->trans('favorite_remove_not_found')
            ], 404);
        }

        $em->remove($favorite);
        $em->flush();

        return new JsonResponse(['status' => 'success']);
    }
}
