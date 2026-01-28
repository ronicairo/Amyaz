<?php

namespace App\Controller;

use DateTime;
use App\Entity\Comment;
use App\Entity\Favorite;
use App\Form\CommentType;
use App\Entity\Traduction;
use App\Entity\GrammarSheet;
use App\Form\NewsletterType;
use App\Service\SendMailService;
use App\Repository\CommentRepository;
use App\Entity\NewsletterSubscription;
use App\Repository\FavoriteRepository;
use App\Repository\TraductionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GrammarSheetRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\NewsletterSubscriptionRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends AbstractController
{

    private TranslatorInterface $translator;
    private Security $security;
    public function __construct(TranslatorInterface $translator, Security $security)
    {
        $this->translator = $translator;
        $this->security = $security;
    }

    #[Route('/', name: 'show_home')]
    /**
     * Page d'accueil
     *
     * @return void
     */
    public function showHome(
        TraductionRepository $traductionRepository,
        FavoriteRepository $favoriteRepository,
        NewsletterSubscriptionRepository $newsletterRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        CommentRepository $repoComment,
        GrammarSheetRepository $grammarSheetRepository // Injecter le repository
    ): Response {

        $searchTerm = $request->query->get('q');
        $langOption = $request->query->get('lang', $request->query->get('selected_lang', ''));

        // Initialiser les variables error et sanitizedSearchTerm
        $error = '';
        $sanitizedSearchTerm = '';

        // Vérifier la présence de balises HTML dans le terme de recherche
        if ($searchTerm && $this->containsHtml($searchTerm)) {
            $error = 'Le terme de recherche ne doit pas contenir de code HTML.';
        } else {
            // Assainir le terme de recherche pour éviter le code HTML ou les balises
            $sanitizedSearchTerm = htmlspecialchars($searchTerm, ENT_NOQUOTES, 'UTF-8');

            // Validation de la longueur du terme de recherche
            if (strlen($sanitizedSearchTerm) > 60) {
                $sanitizedSearchTerm = substr($sanitizedSearchTerm, 0, 60); // Tronquer le terme si nécessaire
            }
        }

        // Recherche des traductions
        $traductions = $sanitizedSearchTerm
            ? $traductionRepository->findBySearchTerm($sanitizedSearchTerm, $langOption)
            : [];

            $user = $this->security->getUser();

            $favorites = [];
            if ($user) {
                $favorites = $favoriteRepository->findBy(['user' => $user]);
            }

        // Compteur dictionnaire
            $wordCount = $traductionRepository->countSingularWords();
            $translatedMessage = $this->translator->trans('countword', ['{{ count }}' => $wordCount]);

        // Récupération des commentaires (non supprimés) pour l'affichage
        $commentaires = $repoComment->createQueryBuilder('c')
            ->where('c.user IS NOT NULL')
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
        // Pagination des traductions
        $pagination = $paginator->paginate(
            $traductions,
            $request->query->getInt('page', 1),
            6
        );

        // Pagination des commentaires
        $paginationComment = $paginator->paginate(
            $commentaires,
            $request->query->getInt('page', 1),
            3
        );

        // Gestion du formulaire de commentaire
        $commentaire = new Comment();
        $form = $this->createForm(CommentType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $existingComment = $entityManager->getRepository(Comment::class)
                ->findOneBy(['user' => $user]);

            if ($existingComment) {
                $this->addFlash('warning', $this->translator->trans('addflash.only_one_comment'));
                return $this->redirectToRoute('show_home');
            }
            if (!$user) {
                $this->addFlash('warning', $this->translator->trans('addflash.obligation_connexion'));
                return $this->redirectToRoute('show_home');
            }

            if (!$user->isVerified()) {
                $resendVerificationUrl = $this->generateUrl('app_resend_verification_code');

                $verificationMessage = $this->translator->trans('addflash.verification_compte', [
                    '%resend_verification_url%' => $resendVerificationUrl
                ]);
                $this->addFlash('error', $verificationMessage);
                return $this->redirectToRoute('account');
            }

            $commentaire->setUser($user);
            $commentaire->setCreatedAt(new DateTime());
            $commentaire->setUpdatedAt(new DateTime());

            $repoComment->save($commentaire, true);

            $this->addFlash('success', $this->translator->trans('addflash.comment_success'));
            return $this->redirectToRoute('show_home');
        }

        // Mot du jour
        $dayOfYear = (int) date('z'); // Day of the year (0-365)
        $totalWords = $traductionRepository->count([]); // Assuming count method exists
        $wordOfTheDay = $traductionRepository->findWordOfTheDay($dayOfYear % $totalWords);

        $grammarSheets = $grammarSheetRepository->findAll();

        $subscription = new NewsletterSubscription();
        $formNews = $this->createForm(NewsletterType::class, $subscription);
        $formNews->handleRequest($request);

        if ($formNews->isSubmitted() && $formNews->isValid()) {
            $email = $subscription->getEmail();

            // Check if the email already exists
            $existingSubscription = $entityManager->getRepository(NewsletterSubscription::class)
                ->findOneBy(['email' => $email]);

            if ($existingSubscription) {
                $this->addFlash('error',  $this->translator->trans('addflash.already_subscribed'));
            } else {
                $subscription->setCreatedAt(new DateTime());
                $subscription->setUpdatedAt(new DateTime());

                $newsletterRepository->save($subscription, true);
                $this->addFlash('success', $this->translator->trans('addflash.subscribed_success'));
            }

            return $this->redirectToRoute('show_home');
        }
        return $this->render('default/show_home.html.twig', [
            'traductions' => $traductions,
            'favorites' => $favorites,
            'langOption' => $langOption,
            'pagination' => $pagination,
            'commentaires' => $commentaires,
            'paginationComment' => $paginationComment,
            'sanitizedSearchTerm' => $sanitizedSearchTerm,
            'form' => $form->createView(),
            'wordCount' => $wordCount,
            'translatedMessage' => $translatedMessage,
            'formNews' => $formNews->createView(),
            'error' => $error,
            'wordOfTheDay' => $wordOfTheDay,
            'grammarSheets' => $grammarSheets
        ]);
    }


    #[Route('/unsubscribe', name: 'newsletter_unsubscribe', methods: ['GET', 'POST'])]
    /**
     * Désinscription newsletters
     *
     * @param  mixed $request
     * @param  mixed $newsletterRepository
     * @param  mixed $entityManager
     * @return Response
     */
    public function unsubscribe(Request $request, NewsletterSubscriptionRepository $newsletterRepository, EntityManagerInterface $entityManager,): Response
    {
        $email = $request->query->get('email');

        if ($email) {
            $subscription = $newsletterRepository->findOneBy(['email' => $email]);

            if ($subscription) {
                $entityManager->remove($subscription);
                $entityManager->flush();

                $this->addFlash('success', $this->translator->trans('addflash.unsubscribed_success'));
            } else {
                $this->addFlash('error', $this->translator->trans('addflash.email_not_found'));
            }
        } else {
            $this->addFlash('error', $this->translator->trans('addflash.email_non_specifie'));
        }

        return $this->redirectToRoute('show_home'); // Rediriger vers la page d'accueil ou une autre page
    }

    #[Route('/supprimer-un-commentaire/{id}', name: 'hard_delete_commentaire', methods: ['GET'])]
    /**
     * Suppression d'un commentaire
     *
     * @param  mixed $commentaire
     * @param  mixed $repository
     * @param  mixed $security
     * @return Response
     */
    public function hardDeleteCommentaire(Comment $commentaire, CommentRepository $repository, Security $security): Response
    {
        $user = $this->getUser();

        // Vérifie si l'utilisateur est l'auteur du commentaire ou un administrateur
        if ($user === $commentaire->getUser() || $security->isGranted('ROLE_ADMIN')) {
            $repository->remove($commentaire, true);
            $this->addFlash('success', $this->translator->trans('addflash.comment_deleted_success'));
        } else {
            $this->addFlash('error', $this->translator->trans('addflash.comment_delete_denied'));
        }


        return $this->redirectToRoute('show_home');
    }

    /**
     * Vérifie si une chaîne contient des balises HTML.
     */
    private function containsHtml($string)
    {
        return $string !== strip_tags($string);
    }

    #[Route('/mentions_legales', name: 'mentions_legale')]
    public function mentionsLegales(): Response
    {
        return $this->render('footer/mentions_legales.html.twig');
    }

    #[Route('/error-404', name: 'error-404')]
    public function show404(): Response
    {
        return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
    }


    #[Route('/favorite/add', name: 'favorite_add_word', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $em, UserInterface $user = null): JsonResponse
    {
        if (!$user) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $this->translator->trans('home.fav_login')
            ], 401);
        }

        $data = json_decode($request->getContent(), true);
        $traductionId = $data['traduction_id'];
        $traduction = $em->getRepository(Traduction::class)->find($traductionId);

        if (!$traduction) {
            return new JsonResponse(['status' => 'error', 'message' => $this->translator->trans('home.no_fav')], 404);
        }

        $favorite = new Favorite();
        $favorite->setUser($user);
        $favorite->setTraduction($traduction);

        $em->persist($favorite);
        $em->flush();

        $favoritesUrl = $this->generateUrl('show_favorites');
        $this->addFlash('success', $this->translator->trans('home.favorite_added', ['{{ url }}' => $favoritesUrl]));

        return new JsonResponse(['status' => 'success']);
    }

    #[Route('/favorite/remove', name: 'favorite_remove_word', methods: ['POST'])]
    public function remove(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->security->getUser();
        if (!$user) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $this->translator->trans('favorite_remove_login_required')
            ], 401);
        }

        $data = json_decode($request->getContent(), true);
        $traductionId = $data['traduction_id'];
        $favorite = $em->getRepository(Favorite::class)->findOneBy([
            'user' => $user,
            'traduction' => $traductionId
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

    #[Route('/alphabet/{letter}', name: 'browse_by_letter')]
/**
 * Parcourir le dictionnaire par lettre alphabétique
 *
 * @param string $letter
 * @param TraductionRepository $traductionRepository
 * @param FavoriteRepository $favoriteRepository
 * @param Request $request
 * @param PaginatorInterface $paginator
 * @return Response
 */
public function browseByLetter(
    string $letter,
    TraductionRepository $traductionRepository,
    FavoriteRepository $favoriteRepository,
    Request $request,
    PaginatorInterface $paginator
): Response {
    // Valider la lettre (a-z uniquement)
    $letter = strtolower($letter);
    if (!preg_match('/^[a-z]$/', $letter)) {
        throw $this->createNotFoundException($this->translator->trans('error.invalid_letter'));
    }

    $locale = $request->getLocale();
    $user = $this->security->getUser();
    
    // Récupérer les traductions commençant par la lettre
    $traductions = $traductionRepository->findByFirstLetter($letter, $locale);
    
    // Récupérer les favoris de l'utilisateur
    $favorites = [];
    if ($user) {
        $favorites = $favoriteRepository->findBy(['user' => $user]);
    }
    
    // Pagination
    $pagination = $paginator->paginate(
        $traductions,
        $request->query->getInt('page', 1),
        20 // 20 résultats par page
    );

    // Déterminer l'option de langue pour l'affichage
    $langOption = $locale === 'en' ? 'en-rif' : 'fr-rif';

    return $this->render('default/browse_alphabet.html.twig', [
    'letter' => strtoupper($letter),
    'traductions' => $traductions,
    'favorites' => $favorites,
    'pagination' => $pagination,
    'langOption' => $langOption
]);
}

}
