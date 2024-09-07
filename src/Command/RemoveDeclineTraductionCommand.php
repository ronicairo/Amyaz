<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:remove-decline-traduction',
    description: 'Supprime les demandes de traductions refusée il y a plus de 7 jours.',
)]
class RemoveDeclineTraductionCommand extends Command
{
    protected static $defaultName = 'app:remove-decline-traduction';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Supprime les demandes de traductions refusée il y a plus de 7 jours.');
    }    

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $now = new \DateTime();
        $sevenDaysAgo = $now->modify('-7 days');

        $query = $this->entityManager->createQuery(
            'DELETE FROM App\Entity\Traduction t
            WHERE t.status = :status
            AND t.updatedAt < :sevenDaysAgo'
        )->setParameter('status', 2)
            ->setParameter('sevenDaysAgo', $sevenDaysAgo);

        $numDeleted = $query->execute();

        $io->success("$numDeleted translations removed.");

        file_put_contents('/Users/Roni/Downloads/Amyaz/cron.log', "Traductions supprimées à " . (new \DateTime())->format('Y-m-d H:i:s') . "\n", FILE_APPEND);

        $output->writeln('Traductions supprimées avec succès.');

        return Command::SUCCESS;
    }
}
