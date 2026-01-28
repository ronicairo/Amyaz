<?php

namespace App\Repository;

use App\Entity\Verbe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Verbe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Verbe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Verbe[]    findAll()
 * @method Verbe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VerbeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Verbe::class);
    }

    // Example custom query to find verbs by French name
    public function findByFrenchVerb(string $verbeFrancais)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.verbeFrancais = :verbe')
            ->setParameter('verbe', $verbeFrancais)
            ->getQuery()
            ->getResult();
    }

    public function findBySearchTerm($key,$value)
    {
        $qb = $this->createQueryBuilder('t');

        // Utilisation de LOWER() pour ignorer la casse dans la requête
        $qb->where("LOWER(TRIM(t.$key)) = :exactTerm OR 
                LOWER(TRIM(t.$key)) LIKE :termStarts OR 
                LOWER(TRIM(t.$key)) LIKE :termEnd OR 
                LOWER(TRIM(t.$key)) LIKE :termContains OR 
                LOWER(TRIM(t.$key)) LIKE :termContainsSpace")
            ->setParameter('exactTerm', $value)
            ->setParameter('termStarts', $value . '%')
            ->setParameter('termContainsSpace', '% ' . $value . ' %')
            ->setParameter('termEnd', '%' . $value)
            ->setParameter('termContains', '%' . $value . '%');



        $results = $qb->getQuery()->getResult();


        return $results;

    }

    public function findByVerbeExact(string $verbeRifain, string $verbeFrancais, string $forme): array
    {
        return $this->createQueryBuilder('v')
            ->where('v.verbeRifain = :verbeRifain')
            ->andWhere('v.verbeFrancais = :verbeFrancais')
            ->andWhere('v.forme = :forme')
            ->setParameter('verbeRifain', $verbeRifain)
            ->setParameter('verbeFrancais', $verbeFrancais)
            ->setParameter('forme', $forme)
            ->getQuery()
            ->getResult();  // Utilise getResult() pour autoriser plusieurs résultats
    }
    

}
