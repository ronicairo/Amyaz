<?php

namespace App\Command;

use App\Entity\Verbe;
use App\Repository\VerbeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-verbes-json',
    description: 'Ajoute les verbes depuis le fichier JSON.',
)]
class ImportVerbJsonCommand extends Command
{
    protected static $defaultName = 'app:import-verbes-json';

    private $entityManager;
    private $verbeRepository;

    public function __construct(EntityManagerInterface $entityManager, VerbeRepository $verbeRepository)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->verbeRepository = $verbeRepository;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Ajoute les verbes depuis le fichier JSON.');
    }  

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
    
        // Chemin vers le fichier JSON
        $jsonFilePath = __DIR__ . '/../../public/verbes.json';
    
        // Lire le contenu du fichier JSON
        if (!file_exists($jsonFilePath)) {
            $io->error("Le fichier verbes.json n'existe pas.");
            return Command::FAILURE;
        }
    
        $jsonData = file_get_contents($jsonFilePath);
        $verbesArray = json_decode($jsonData, true);
    
        if ($verbesArray === null || !isset($verbesArray['items'])) {
            $io->error('Le fichier JSON est invalide ou ne contient pas de section "items".');
            return Command::FAILURE;
        }
    
        // Parcourir les verbes et les ajouter dans la base de données
        $addedVerbes = 0;
        foreach ($verbesArray['items'] as $verbeData) {
            // Rechercher tous les verbes qui correspondent exactement
            $existingVerbes = $this->verbeRepository->findByVerbeExact(
                $verbeData['verbe_rifain'], 
                $verbeData['verbe_francais'], 
                $verbeData['forme']
            );
    
            // Si aucun verbe n'existe, on l'ajoute
            if (empty($existingVerbes)) {
                $verbe = new Verbe();
                $verbe->setVerbeRifain($verbeData['verbe_rifain']);
                $verbe->setVerbeFrancais($verbeData['verbe_francais']);
                $verbe->setVerbeTifinagh($verbeData['verbe_tifinagh']);
                $verbe->setForme($verbeData['forme']);
    
                // Vérifie si les champs R1 à R7 existent avant de les définir, sinon les laisse à une chaîne vide
                $verbe->setR1($verbeData['R1'] ?? "");
                $verbe->setR2($verbeData['R2'] ?? "");
                $verbe->setR3($verbeData['R3'] ?? "");
                $verbe->setR4($verbeData['R4'] ?? "");
                $verbe->setR5($verbeData['R5'] ?? "");
                $verbe->setR6($verbeData['R6'] ?? "");
                $verbe->setR7($verbeData['R7'] ?? "");
    
                $this->entityManager->persist($verbe);
                $addedVerbes++;  // Incrémente le compteur
            }
        }
    
        // Sauvegarder les modifications dans la base de données
        $this->entityManager->flush();
    
        // Afficher le nombre de verbes ajoutés
        $io->success(sprintf('%d verbes ont été ajoutés.', $addedVerbes));
    
        $directory = '//home/u230803854/domains/amyaz.fr/public_html/';
    
        // Vérifie si le répertoire existe, sinon il le crée
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true); // Crée les dossiers manquants avec les permissions 0777
        }
    
        $logFile = $directory . 'cron.log';
    
        // Ajout du message dans le fichier log
        file_put_contents($logFile, sprintf('%d verbes ont été ajoutés à %s', $addedVerbes, (new \DateTime())->format('Y-m-d H:i:s')) . "\n", FILE_APPEND);
    
        return Command::SUCCESS;
    }  
    
}
