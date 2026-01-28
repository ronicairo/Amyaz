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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, TokenGeneratorInterface $tokenGenerator, SendMailService $mail, SessionInterface $session): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
    
        if ($security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('account');
        }
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Générer un code de vérification à 6 chiffres
            $verificationCode = random_int(100000, 999999);
            $user->setVerificationCode($verificationCode);
    
            // Hasher le mot de passe mais ne pas sauvegarder encore
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
    
            $user->setCreatedAt(new DateTime());
            $user->setUpdatedAt(new DateTime());
    
            // Sauvegarder temporairement dans la session
            $session->set('registration_user', $user);
    
            // Envoyer le code par email
            $context = ['code' => $verificationCode, 'user' => $user];
            $mail->send(
                'contact@amyaz.fr',
                $user->getEmail(),
                'Code de vérification pour votre inscription',
                'registration/email_verification_code.html.twig',
                $context
            );
    
            return $this->redirectToRoute('app_verify_code');
        }
    
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    

    #[Route('/verify/code', name: 'app_verify_code')]
    public function verifyCode(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        if ($request->isMethod('POST')) {
            $code = $request->request->get('verification_code');
    
            // Récupérer l'utilisateur de la session
            $user = $session->get('registration_user');
    
            if ($user && $user->getVerificationCode() === $code) {
                $user->setIsVerified(true);
                $user->setVerificationCode(null); // Supprime le code après vérification
    
                // Sauvegarder l'utilisateur dans la base de données
                $entityManager->persist($user);
                $entityManager->flush();
    
                // Supprimer les données de la session
                $session->remove('registration_user');
    
                $this->addFlash('success', 'Votre compte a été vérifié avec succès !');
                return $this->redirectToRoute('account');
            } else {
                $this->addFlash('error', 'Code de vérification incorrect.');
            }
        }
    
        return $this->render('registration/verify_code.html.twig');
    }

#[Route('/verify/email', name: 'app_verify_email')]
public function verifyUserEmail(Request $request, EntityManagerInterface $entityManager, SendMailService $mail): Response
{
    $user = $this->getUser();

    if (!$user || !$user instanceof User || $user->isVerified()) {
        // Si l'utilisateur est déjà vérifié, rediriger
        $this->addFlash('info', 'Votre compte a déjà été vérifié.');
        return $this->redirectToRoute('account');
    }

    // Générer un code de vérification
    $verificationCode = random_int(100000, 999999);
    $user->setVerificationCode($verificationCode);
    $entityManager->flush();

    // Générer l'URL de vérification
    $verificationUrl = $this->generateUrl(
        'app_verify_code', 
        ['code' => $verificationCode], 
        UrlGeneratorInterface::ABSOLUTE_URL
    );

    // Préparer l'email
    $context = [
        'user' => $user,
        'verificationUrl' => $verificationUrl,
        'code' => $verificationCode,
    ];

    // Envoyer l'email
    $mail->send(
        'contact@amyaz.fr',
        $user->getEmail(),
        'Code de vérification pour votre inscription',
        'registration/email_verification_code.html.twig', // Le template
        $context // Le contexte avec 'code' et 'user'
    );

    $this->addFlash('success', 'Un email avec un code de vérification a été envoyé.');

    return $this->redirectToRoute('app_verify_code');
}


#[Route('/resend-verification-code', name: 'app_resend_verification_code')]
public function resendVerificationCode(EntityManagerInterface $entityManager, SendMailService $mail): Response
{
    $user = $this->getUser();

    if (!$user || !$user instanceof User || $user->isVerified()) {
        $this->addFlash('error', 'Vous êtes déjà vérifié ou non connecté.');
        return $this->redirectToRoute('app_login');
    }

    // Générer un nouveau code de vérification
    $verificationCode = random_int(100000, 999999); // Code à 6 chiffres
    $user->setVerificationCode($verificationCode);
    $entityManager->flush();

     // Générer l'URL de vérification de manière absolue
     $verificationUrl = $this->generateUrl(
        'app_verify_code', // Le nom de la route de vérification
        ['code' => $verificationCode], // Paramètres
        UrlGeneratorInterface::ABSOLUTE_URL // Générer l'URL absolue
    );

    // Préparer le contexte pour l'email
    $context = [
        'user' => $user,
        'code' => $verificationCode,
        'verificationUrl' => $verificationUrl, // Utiliser l'URL absolue ici
    ];

    // Envoyer l'email
    $mail->send(
        'contact@amyaz.fr',                  // Adresse de l'expéditeur
        $user->getEmail(),                   // Adresse de l'utilisateur
        'Nouveau code de vérification',      // Sujet
        'registration/email_verification_code.html.twig', // Template d'email
        $context                             // Contexte pour le template
    );

    $this->addFlash('success', 'Un nouveau code de vérification a été envoyé à votre adresse email.');
    return $this->redirectToRoute('app_verify_code');
}

}
