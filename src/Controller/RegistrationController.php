<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Service\SendMailService;
use Symfony\Component\Mime\Email;
use App\Form\RegistrationFormType;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    #[Route('/register', name: 'app_register')]    
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager, TokenGeneratorInterface $tokenGenerator, SendMailService $mail): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($security->isGranted('ROLE_USER')) {
            $this->addFlash('warning', $this->translator->trans('addflash.already_connected'));
            return $this->redirectToRoute('account');
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setCreatedAt(new DateTime());
            $user->setUpdatedAt(new DateTime());

            $token = $tokenGenerator->generateToken();
            $user->setResetToken($token);

            $entityManager->persist($user);
            $entityManager->flush();

            $url = $this->generateUrl('app_verify_email', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            $context = compact('url', 'user');

            $mail->send(
                'contact@amyaz.fr',
                $user->getEmail(),
                'Activation de votre compte Amyaz',
                'registration/email_activation_compte.html.twig',
                $context
            );

            $this->addFlash('success', $this->translator->trans('addflash.activation_email_sent'));

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]    
    public function verifyUserEmail(Request $request, EntityManagerInterface $entityManager): Response
    {
        $token = $request->get('token');

        if (!$token) {
            throw $this->createNotFoundException('No token found in URL.');
        }

        $user = $entityManager->getRepository(User::class)->findOneBy(['resetToken' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('No user found for this token.');
        }

        if ($user->isVerified()) {
            $this->addFlash('info', $this->translator->trans('addflash.account_already_verified'));
            return $this->redirectToRoute('account');
        }

        $user->setIsVerified(true);
        $user->setResetToken(null);
        $entityManager->flush();


        $this->addFlash('success', $this->translator->trans('addflash.email_verified_success'));

        return $this->redirectToRoute('show_home');
    }

    #[Route('/resend/verification-email', name: 'app_resend_verification_email', methods: ['GET'])]    
    public function resendVerificationEmail(EntityManagerInterface $entityManager, MailerInterface $mailer, UrlGeneratorInterface $urlGenerator, SendMailService $mail): Response
    {
        $user = $this->getUser();

        if (!$user || !$user instanceof User || $user->isVerified()) {
            $this->addFlash('error', $this->translator->trans('addflash.no_verification_needed_or_not_logged_in'));
            return $this->redirectToRoute('app_login');
        }

        // Génère un token et l'enregistre
        $token = bin2hex(random_bytes(32));
        $user->setResetToken($token);
        $entityManager->flush();

        // Generate le lien de vérification
        $url = $this->generateUrl('app_verify_email', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

        $context = compact('url', 'user');

        $mail->send(
            'contact@amyaz.fr',
            $user->getEmail(),
            'Activation de votre compte Amyaz',
            'registration/email_activation_compte.html.twig',
            $context
        );


        $this->addFlash('success', $this->translator->trans('addflash.verification_email_sent'));

        return $this->redirectToRoute('show_home');
    }
}
