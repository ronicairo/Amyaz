<?php

namespace App\Command;

use App\Service\SendMailService;
use App\Repository\TraductionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use App\Repository\NewsletterSentWordsRepository;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use App\Repository\NewsletterSubscriptionRepository;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsCommand(
    name: 'app:send-newsletter',
    description: 'Envoi newsletter hebdo',
)]
class SendNewsletterCommand extends Command
{
    protected static $defaultName = 'app:send-newsletter';

    private $sendMailService;
    private $traductionRepository;
    private $newsletterRepository;
    private $newsletterSentWordsRepository;
    private $urlGenerator;

    public function __construct(
        SendMailService $sendMailService,
        TraductionRepository $traductionRepository,
        NewsletterSubscriptionRepository $newsletterRepository,
        NewsletterSentWordsRepository $newsletterSentWordsRepository,
        UrlGeneratorInterface $urlGenerator
    ) {
        parent::__construct();
        $this->sendMailService = $sendMailService;
        $this->traductionRepository = $traductionRepository;
        $this->newsletterRepository = $newsletterRepository;
        $this->newsletterSentWordsRepository = $newsletterSentWordsRepository;
        $this->urlGenerator = $urlGenerator;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Envoie une newsletter hebdomadaire à tous les abonnés.')
            ->addOption('limit', null, InputOption::VALUE_OPTIONAL, 'Le nombre de mots à envoyer', 10);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        
    $limit = $input->getOption('limit');
    
    // Charger les IDs des mots déjà envoyés
    $sentWordIds = $this->newsletterSentWordsRepository->findSentWordIds();

    // Récupérer les nouveaux mots à envoyer
    $words = $this->traductionRepository->findRecentWords($limit, $sentWordIds);

    if (empty($words)) {
        $output->writeln('Aucun nouveau mot à envoyer.');
        return Command::SUCCESS;
    }

    $subscriptions = $this->newsletterRepository->findAll();

        foreach ($subscriptions as $subscription) {
            $unsubscribeUrl = $this->urlGenerator->generate(
                'newsletter_unsubscribe',
                ['email' => $subscription->getEmail()],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

               // Vérifier si l'URL contient localhost et la remplacer par APP_URL
        if (strpos($unsubscribeUrl, 'http://localhost') === 0) {
            $unsubscribeUrl = str_replace('http://localhost', $_ENV['APP_URL'], $unsubscribeUrl);
        }

        // Préparer le contexte pour l'email avec l'URL de désinscription corrigée
        $context = [
            'words' => $words,
            'subscriberEmail' => $subscription->getEmail(),
            'unsubscribeUrl' => $unsubscribeUrl, // Passer l'URL de désinscription dans le contexte
        ];

            $this->sendMailService->send(
                'contact@amyaz.fr',
                $subscription->getEmail(),
                'Votre liste des mots rifain de la semaine',
                'newsletter/words.html.twig',
                $context
            );
        }

      // Sauvegarder les IDs des mots envoyés
        $sentWordIds = array_column($words, 'id');
        $this->newsletterSentWordsRepository->saveSentWordIds($sentWordIds);
        
       file_put_contents('/home/u230803854/domains/amyaz.fr/public_html/cron.log', "Emails envoyés avec succès à " . (new \DateTime())->format('Y-m-d H:i:s') . "\n", FILE_APPEND);
        $output->writeln('Emails envoyés avec succès!');
        return Command::SUCCESS;
    }
}
