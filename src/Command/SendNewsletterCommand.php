<?php

namespace App\Command;

use App\Service\SendMailService;
use App\Repository\TraductionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
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
    private $urlGenerator;

    public function __construct(
        SendMailService $sendMailService,
        TraductionRepository $traductionRepository,
        NewsletterSubscriptionRepository $newsletterRepository,
        UrlGeneratorInterface $urlGenerator
    ) {
        parent::__construct();
        $this->sendMailService = $sendMailService;
        $this->traductionRepository = $traductionRepository;
        $this->newsletterRepository = $newsletterRepository;
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
        $words = $this->traductionRepository->findRecentWords($limit);

        $subscriptions = $this->newsletterRepository->findAll();
        // $emails = array_map(fn($sub) => $sub->getEmail(), $subscriptions);

        $context = [
            'words' => $words
        ];

        foreach ($subscriptions as $subscription) {
            $unsubscribeUrl = $this->urlGenerator->generate(
                'newsletter_unsubscribe',
                ['email' => $subscription->getEmail()],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

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

        file_put_contents('/Users/Roni/Downloads/Amyaz/cron.log', "Emails envoyés avec succès à " . (new \DateTime())->format('Y-m-d H:i:s') . "\n", FILE_APPEND);
        $output->writeln('Emails envoyés avec succès!');
        return Command::SUCCESS;
    }
}
