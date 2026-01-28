<?php

namespace App\Service;

use App\Entity\Verbe;
use App\Repository\VerbeRepository;
use Doctrine\ORM\EntityManagerInterface;

class VerbeService
{
    private $verbeRepository;
    private $em;

    private $mapping = array(
        'fr-rif'  => 'verbeFrancais',
        'rif-fr' => 'verbeRifain',
    );

    public function __construct(VerbeRepository $verbeRepository, EntityManagerInterface $em)
    {
        $this->verbeRepository = $verbeRepository;
        $this->em = $em;
    }

    public function countVerbes(): int
    {
        return $this->verbeRepository->count([]);
    }

    public function findBySearchTerm($searchTerm, $lang): array
    {
        $key = $this->mapping[$lang];

        // Supprimer les espaces au début et à la fin du terme de recherche
        $trimmedSearchTerm = trim($searchTerm);

        // Convertir le terme de recherche en minuscules
        $loweredSearchTerm = mb_strtolower($trimmedSearchTerm, 'UTF-8');


        $results =  $this->verbeRepository->findBySearchTerm($key,$loweredSearchTerm);

        usort($results, function($a, $b) use ($loweredSearchTerm, $key,$lang) {

            $function = "get".$this->mapping[$lang];
            $wordA = trim($a->$function());
            $wordB = trim($b->$function());

            $exactMatchA = ($wordA === $loweredSearchTerm || strpos($wordA, ', '.$loweredSearchTerm) !== false || strpos($wordA, $loweredSearchTerm.',') !== false);
            $exactMatchB = ($wordB === $loweredSearchTerm || strpos($wordB, ', '.$loweredSearchTerm) !== false || strpos($wordB, $loweredSearchTerm.',') !== false);


            if ($exactMatchA && !$exactMatchB) {
                return -1;
            } elseif (!$exactMatchA && $exactMatchB) {
                return 1;
            }

            $posA = strpos($wordA, $loweredSearchTerm);
            $posB = strpos($wordB, $loweredSearchTerm);

            if ($posA !== $posB) {
                return $posA - $posB;
            }

            return strlen($wordA) - strlen($wordB);
        });



        $response = array();
        $function = "get".$this->mapping[$lang];
        foreach ($results as $traduction) {
            $response[]= $traduction->$function();
        }


        return $response;

    }
}
