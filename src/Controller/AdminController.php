<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\SendMailService;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use App\Form\PasswordConfirmationType;
use App\Repository\TraductionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{

    private TranslatorInterface $translator;
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    #[Route('/user-list', name: 'app_user_index')]
    /**
     * Voir la liste des utilisateurs
     *
     * @param  mixed $userRepository
     * @param  mixed $paginator
     * @param  mixed $request
     * @return Response
     */
     
   public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $userRepository->createQueryBuilder('u')
            ->orderBy('u.createdAt', 'DESC'); // Sort by createdAt DESC

        // Pagination des utilisateurs
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        // Nombre total d'utilisateurs
        $totalUsers = $userRepository->count([]);

        // Nombre d'utilisateurs vérifiés
        $verifiedUsersCount = $userRepository->count(['isVerified' => true]);

        // Nombre d'utilisateurs non vérifiés
        $unverifiedUsersCount = $userRepository->count(['isVerified' => false]);

        return $this->render('account/user/index.html.twig', [
            'pagination' => $pagination,
            'totalUsers' => $totalUsers,
            'verifiedUsersCount' => $verifiedUsersCount,
            'unverifiedUsersCount' => $unverifiedUsersCount,
        ]);
    }

    #[Route('/user/{id}', name: 'app_user_show', methods: ['GET'])]
    /**
     * Affiche les détails d'un utilisateur spécifique.
     *
     * @param User $user
     * @param CommentRepository $commentRepository
     * @param TraductionRepository $traductionRepository
     * @return Response
     */
    public function show(User $user, CommentRepository $commentRepository, TraductionRepository $traductionRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', $this->translator->trans('addflash.restreindre_acces'));
            return $this->redirectToRoute('account');
        }

        $comments = $commentRepository->findBy(['user' => $user]);
        $traductions = $traductionRepository->findBy(['requestedBy' => $user]);

        return $this->render('account/user/show.html.twig', [
            'user' => $user,
            'comments' => $comments,
            'traductions' => $traductions,
        ]);
    }

      #[Route('/user/{id}/change-role', name: 'app_user_change_role', methods: ['POST'])]
        public function changeRole(Request $request, User $user, EntityManagerInterface $em, SendMailService $mail): Response
        {
    
            if (!$this->isGranted('ROLE_ADMIN')) {
                $this->addFlash('error', $this->translator->trans('addflash.restreindre_acces'));
                return $this->redirectToRoute('account');
            }
    
            // Empêcher un administrateur de changer son propre rôle
            $currentUser = $this->getUser();
            if ($user === $currentUser) {
                $this->addFlash('error', 'Vous ne pouvez pas changer votre propre rôle.');
                return $this->redirectToRoute('app_user_index');
            }
    
            $newRole = $request->request->get('role');
    
            if ($newRole && in_array($newRole, ['ROLE_USER', 'ROLE_MODERATOR', 'ROLE_ADMIN'])) {
                $user->setRoles([]);
                $user->setRoles([$newRole]);
    
                $em->persist($user);
                $em->flush();
    
                $context = [
                    'user' => $user,
                    'newRole' => $newRole
                ];
        
                $mail->send(
                    'contact@amyaz.fr',
                    $user->getEmail(),
                    'Changement de rôle',
                    'security/email_update_role.html.twig',
                    $context
                );
    
                $this->addFlash('success', 'Rôle mis à jour avec succès.');
            } else {
                $this->addFlash('error', 'Rôle invalide.');
            }
    
            return $this->redirectToRoute('app_user_index');
        }


    #[Route('/user/{id}/delete', name: 'admin_user_delete', methods: ['GET', 'POST'])]
    public function deleteUser(
        Request $request,
        User $user,
        UserRepository $repository,
        UserPasswordHasherInterface $passwordHasher,
        Security $security
    ): Response {
        try {
            $this->denyAccessUnlessGranted("ROLE_ADMIN");
        } catch (AccessDeniedException) {
            $this->addFlash('danger', $this->translator->trans('addflash.restreindre_acces'));
            return $this->redirectToRoute('account');
        }

        $currentAdmin = $security->getUser();

        if (!$currentAdmin instanceof PasswordAuthenticatedUserInterface) {
            $this->addFlash('error', "Une erreur s'est produite. Veuillez réessayer.");
            return $this->redirectToRoute('account');
        }

        $confirmationForm = $this->createForm(PasswordConfirmationType::class);
        $confirmationForm->handleRequest($request);

        if ($confirmationForm->isSubmitted() && $confirmationForm->isValid()) {
            $submittedData = $confirmationForm->getData();

            if ($passwordHasher->isPasswordValid($currentAdmin, $submittedData['password'])) {
                $user->setDeletedAt(new \DateTime());
                $repository->remove($user, true);

                $this->addFlash('success', "L'utilisateur a bien été supprimé.");
                return $this->redirectToRoute('app_user_index');
            } else {
                $this->addFlash('error', "Mot de passe incorrect. La suppression du compte a échoué.");
            }
        }

        return $this->render('account/user/delete_user.html.twig', [
            'user' => $user,
            'confirmationForm' => $confirmationForm->createView(),
        ]);
    }
}
