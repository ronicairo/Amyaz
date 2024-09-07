<?php

namespace App\Repository;

use App\Entity\Traduction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Traduction>
 *
 * @method Traduction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Traduction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Traduction[]    findAll()
 * @method Traduction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TraductionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Traduction::class);
    }

    public function findAllArchived()
    {
        return $this->createQueryBuilder('t')
            ->where('t.deletedAt IS NOT NULL')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySearchTerm($searchTerm, $langOption)
    {
        $qb = $this->createQueryBuilder('t')
            ->leftJoin('t.status', 's')
            ->addSelect('s');
    
        // Supprimer les espaces au début et à la fin du terme de recherche
        $trimmedSearchTerm = trim($searchTerm);
    
        // Ajouter la condition principale en fonction de la langue choisie
        if ($langOption === 'fr-rif') {
            $qb->where('TRIM(t.wordFR) = :exactTerm OR TRIM(t.wordFR) LIKE :termStarts OR TRIM(t.wordFR) LIKE :termContains')
                ->setParameter('exactTerm', $trimmedSearchTerm)
                ->setParameter('termStarts', $trimmedSearchTerm . '%')
                ->setParameter('termContains', '%' . $trimmedSearchTerm . '%');
        } elseif ($langOption === 'rif-fr' || $langOption === 'rif-en') {
            $qb->where('(TRIM(t.singular) = :exactTerm OR TRIM(t.singular) LIKE :termStarts OR TRIM(t.singular) LIKE :termContains OR TRIM(t.plural) = :exactTerm OR TRIM(t.plural) LIKE :termStarts OR TRIM(t.plural) LIKE :termContains)')
                ->setParameter('exactTerm', $trimmedSearchTerm)
                ->setParameter('termStarts', $trimmedSearchTerm . '%')
                ->setParameter('termContains', '%' . $trimmedSearchTerm . '%');
        } elseif ($langOption === 'en-rif') {
            $qb->where('TRIM(t.wordEN) = :exactTerm OR TRIM(t.wordEN) LIKE :termStarts OR TRIM(t.wordEN) LIKE :termContains')
                ->setParameter('exactTerm', $trimmedSearchTerm)
                ->setParameter('termStarts', $trimmedSearchTerm . '%')
                ->setParameter('termContains', '%' . $trimmedSearchTerm . '%');
        }
    
        // Ajouter la condition pour exclure les traductions avec status_id = 1 ou 2
        // tout en permettant les status_id NULL
        $qb->andWhere('(s.id NOT IN (:excludedStatuses) OR s.id IS NULL)')
            ->setParameter('excludedStatuses', [1, 2]);  // Liste des statuts à exclure
    
        // Trier les résultats pour donner la priorité aux termes qui correspondent exactement, puis commencent par le terme recherché
        $qb->orderBy('CASE 
                        WHEN TRIM(t.wordFR) = :exactTerm THEN 0
                        WHEN TRIM(t.singular) = :exactTerm THEN 0
                        WHEN TRIM(t.plural) = :exactTerm THEN 0
                        WHEN TRIM(t.wordEN) = :exactTerm THEN 0
                        WHEN TRIM(t.wordFR) LIKE :termStarts THEN 1
                        WHEN TRIM(t.singular) LIKE :termStarts THEN 1
                        WHEN TRIM(t.plural) LIKE :termStarts THEN 1
                        WHEN TRIM(t.wordEN) LIKE :termStarts THEN 1
                        ELSE 2 
                        END', 'ASC')
            ->addOrderBy('LENGTH(t.wordFR)', 'ASC')
            ->addOrderBy('LENGTH(t.singular)', 'ASC')
            ->addOrderBy('LENGTH(t.wordEN)', 'ASC');
    
        return $qb->getQuery()
            ->getResult();
    }
    


    public function findRecentWords($limit = 10)
    {
        return $this->createQueryBuilder('t')
            ->select('t.wordFR', 't.wordEN', 't.singular', 't.plural', 't.phonetic_singular', 't.phonetic_plural')
            ->where('t.status = 3 OR t.status = 4 OR t.status IS NULL') // Condition sur le status_id
            ->orderBy('t.createdAt', 'DESC') // Trier par date de création décroissante
            ->setMaxResults($limit) // Limiter à 10 résultats
            ->getQuery()
            ->getArrayResult();
    }

    public function findWordOfTheDay(int $offset)
    {
        return $this->createQueryBuilder('t')
            ->setFirstResult($offset)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }


    //    /**
    //     * @return Traduction[] Returns an array of Traduction objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Traduction
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
