<?php

namespace App\Command;

use App\Service\SendMailService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsCommand(
    name: 'app:send-unverified-users',
    description: 'Envoie un rappel aux utilisateurs non vérifiés et supprime leur compte dans les 7 jours',
)]
class SendUnverifiedUsersCommand extends Command
{
    private $sendMailService;
    private EntityManagerInterface $entityManager;
    private $userRepository;
    private $translator;
    private RouterInterface $router;

    public function __construct(
        SendMailService $sendMailService,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        parent::__construct();
        $this->sendMailService = $sendMailService;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->router = $router;
    }

    protected function configure(): void
    {
        $this
            ->setDescription("Envoie un rappel aux utilisateurs non vérifiés et supprime leur compte dans les 7 jours")
            ->addOption('limit', null, InputOption::VALUE_OPTIONAL, 'Limite du nombre d\'emails envoyés', 50)
            ->addOption('offset', null, InputOption::VALUE_OPTIONAL, 'Décalage des utilisateurs', 0);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $limit = $input->getOption('limit');
        $offset = $input->getOption('offset');
        $io = new SymfonyStyle($input, $output);
        $subject = $this->translator->trans('Votre compte sera désactivé dans 7 jours');
        $today = new \DateTime();
        $weekAgo = (new \DateTime())->modify('-7 days');
        
        // Compteurs pour le nombre de mails envoyés et de comptes supprimés
        $mailCount = 0;
        $deleteCount = 0;
        
        // 1. Envoyer un rappel aux utilisateurs non vérifiés
        $usersToRemind = $this->userRepository->findUnverifiedUsersWithLimitAndOffset($limit, $offset);
        
        $loginUrl = $this->router->generate(
            'app_login', // Remplacez par la route correcte
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        
        // Vérifier si l'URL contient localhost et la remplacer par APP_URL
        if (strpos($loginUrl, 'http://localhost') === 0) {
            $loginUrl = str_replace('http://localhost', $_ENV['APP_URL'], $loginUrl);
        }
        
        if (count($usersToRemind) === 0) {
            $output->writeln("Tous les utilisateurs ont été notifiés.");
            return Command::SUCCESS;
        }
        
        foreach ($usersToRemind as $user) {
            // Préparation du contexte pour l'email avec le lien vers le profil
            $context = [
                'username' => $user->getUsername(),
                'loginUrl' => $loginUrl
            ];
        
            // Envoi de l'email
            $this->sendMailService->send(
                'contact@amyaz.fr',
                $user->getEmail(),
                $subject,
                'registration/unverified_users.html.twig',
                $context
            );
        
            // Mise à jour de la date d'envoi du rappel
            $user->setReminderSentAt($today);
            $mailCount++;
            $output->writeln("Rappel envoyé à : " . $user->getEmail());
        }
        
        $this->entityManager->flush();
        
        // 2. Supprimer les utilisateurs non vérifiés depuis plus de 7 jours après le rappel
        $expiredUsers = $this->userRepository->findExpiredUnverifiedUsers($weekAgo);
        
        foreach ($expiredUsers as $expiredUser) {
            $this->entityManager->remove($expiredUser);
            $deleteCount++;
            $output->writeln("Utilisateur supprimé : " . $expiredUser->getEmail());
        }
        
        $this->entityManager->flush();
        
        // Enregistrement du succès de l'opération dans un fichier log avec le nombre d'actions
        file_put_contents('/home/u230803854/domains/amyaz.fr/public_html/cron.log',
            sprintf(
                "[%s] Rappels envoyés: %d | Comptes supprimés: %d\n",
                $today->format('Y-m-d H:i:s'),
                $mailCount,
                $deleteCount
            ),
            FILE_APPEND
        );
        
        // Affichage des résultats finaux
        $io->success("$mailCount rappel(s) envoyé(s) et $deleteCount compte(s) supprimé(s).");
        
        return Command::SUCCESS;
    }
}
