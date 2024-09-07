<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use Symfony\Component\Form\FormError;
use App\Form\PasswordConfirmationType;
use App\Repository\TraductionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    private TranslatorInterface $translator;
    private TokenStorageInterface $tokenStorage;

    public function __construct(TranslatorInterface $translator, TokenStorageInterface $tokenStorage)
    {
        $this->translator = $translator;
        $this->tokenStorage = $tokenStorage;
    }

    #[Route('/user/{id}/delete', name: 'app_user_delete', methods: ['GET', 'POST'])]
    /**
     * Suppression de son compte
     *
     * @return void
     */
    public function deleteMyAccount(
        Request $request,
        User $user,
        UserRepository $repository,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $confirmationForm = $this->createForm(PasswordConfirmationType::class);
        $confirmationForm->handleRequest($request);

        if ($confirmationForm->isSubmitted() && $confirmationForm->isValid()) {
            $submittedData = $confirmationForm->getData();

            if ($passwordHasher->isPasswordValid($user, $submittedData['password'])) {
                $user->setDeletedAt(new DateTime());
                $repository->remove($user, true);
                $this->logoutUser($request);

                $this->addFlash('success', $this->translator->trans('addflash.compte_supprimer'));
                return $this->redirectToRoute('show_home');
            } else {
                $this->addFlash('error', $this->translator->trans('addflash.compte_supprimer_echec'));
            }
        }

        return $this->render('account/user/delete_my_account.html.twig', [
            'user' => $user,
            'confirmationForm' => $confirmationForm->createView(),
        ]);
    }


    /**
     * Helper method to log out the user and invalidate the session.
     */
    private function logoutUser(Request $request): void
    {
        // Invalidate the session
        $request->getSession()->invalidate();

        // Clear the security token
        $this->tokenStorage->setToken(null);
    }
}
