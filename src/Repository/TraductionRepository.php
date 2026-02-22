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

    private $mapping = array(
      'fr-rif'  => 'wordFR',
      'rif-fr' => 'singular',
      'en-rif' => 'wordEN',
      'rif-en' => 'singular'

    );
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Traduction::class);
    }
    
       public function countSingularWords(): int
    {
        return $this->createQueryBuilder('t')
            ->select('count(t.id)')
            ->where('t.singular IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    public function findAllArchived()
    {
        return $this->createQueryBuilder('t')
            ->where('t.deletedAt IS NOT NULL')
            ->getQuery() # Permet de récupérer la requête SQL
            ->getResult() # Permet de récupérer els résultats de la requête
        ;
    }

    public function findBySearchTerm($searchTerm, $langOption)
    {
    $qb = $this->createQueryBuilder('t')
        ->leftJoin('t.status', 's')
        ->addSelect('s');
    
    $word = $this->mapping[$langOption];
    
    // Supprimer les espaces au début et à la fin du terme de recherche
    $trimmedSearchTerm = trim($searchTerm);
    
    // Convertir le terme de recherche en minuscules
    $loweredSearchTerm = mb_strtolower($trimmedSearchTerm, 'UTF-8');
    
    // Utilisation de LOWER() pour ignorer la casse dans la requête
    $qb->where("LOWER(TRIM(t.$word)) = :exactTerm OR 
                LOWER(TRIM(t.$word)) LIKE :termStarts OR 
                LOWER(TRIM(t.$word)) LIKE :termEnd OR 
                LOWER(TRIM(t.$word)) LIKE :termContains OR 
                LOWER(TRIM(t.$word)) LIKE :termContainsSpace")
        ->setParameter('exactTerm', $loweredSearchTerm)
        ->setParameter('termStarts', $loweredSearchTerm . '%')
        ->setParameter('termContainsSpace', '% ' . $loweredSearchTerm . ' %')
        ->setParameter('termEnd', '%' . $loweredSearchTerm)
        ->setParameter('termContains', '%' . $loweredSearchTerm . '%');
    
    // Ajouter la condition pour exclure les traductions avec status_id = 1 ou 2
    $qb->andWhere('(s.id NOT IN (:excludedStatuses) OR s.id IS NULL)')
        ->setParameter('excludedStatuses', [1, 2]);
    
    // Trier les résultats pour donner la priorité aux termes qui correspondent exactement
    $qb->orderBy("CASE 
                    WHEN LOWER(TRIM(t.$word)) = :exactTerm THEN 0
                    WHEN LOWER(TRIM(t.$word)) LIKE :termStarts THEN 1
                    WHEN LOWER(TRIM(t.$word)) LIKE :termContainsSpace THEN 2
                    WHEN LOWER(TRIM(t.$word)) LIKE :termEnd THEN 3
                    WHEN LOWER(TRIM(t.$word)) LIKE :termContains THEN 4
                    ELSE 5
                END", "ASC")
        ->addOrderBy("LENGTH(t.$word)", 'ASC');

$results = $qb->getQuery()->getResult();



        usort($results, function($a, $b) use ($loweredSearchTerm, $word,$langOption) {
            $function = "get".$this->mapping[$langOption];
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

             foreach ($results as $key => $result) {
            $rifainSingularRecord = $result->getRifainSingularRecord();
            $rifainPluralRecord = $result->getRifainPluralRecord();
            
            // Vérifier si les flux ne sont pas nuls avant de les lire
            $results[$key]->hasRifainSingularRecord = $rifainSingularRecord && is_resource($rifainSingularRecord) && !empty(stream_get_contents($rifainSingularRecord));
            $results[$key]->hasRifainPluralRecord = $rifainPluralRecord && is_resource($rifainPluralRecord) && !empty(stream_get_contents($rifainPluralRecord));
        }

        return $results;
    }


    public function findRecentWords($limit = 10, $excludeIds = [])
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t.id', 't.wordFR', 't.wordEN', 't.singular', 't.plural', 't.phonetic_singular', 't.phonetic_plural')
            ->where('t.status = 3 OR t.status = 4 OR t.status IS NULL')
            ->orderBy('t.createdAt', 'DESC')
            ->setMaxResults($limit);

        if (!empty($excludeIds)) {
            $qb->andWhere('t.id NOT IN (:excludeIds)')
                ->setParameter('excludeIds', $excludeIds);
        }

        return $qb->getQuery()->getArrayResult();
    }

    public function findWordOfTheDay(int $offset)
    {
        return $this->createQueryBuilder('t')
            ->setFirstResult($offset)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllSortedByUpdatedAt(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }    
    
    public function findAllAlphabetically(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.wordFR', 'ASC')
            ->getQuery()
            ->getResult();
    }        

    public function findAllWithRifainSingularRecord(): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.rifainSingularRecord IS NOT NULL')
            ->andWhere('t.rifainSingularRecord != :empty')
            ->setParameter('empty', '')
            ->getQuery()
            ->getResult();
    } 

    public function findByFirstLetter(string $letter, string $locale): array
    {
        $field = $locale === 'en' ? 'wordEN' : 'wordFR';
        
        $possibleChars = $this->getAccentedChars($letter);

        $results = $this->createQueryBuilder('t')
            ->leftJoin('t.status', 's')
            ->addSelect('s')
            ->where('LOWER(SUBSTRING(TRIM(t.' . $field . '), 1, 1)) IN (:letters)')
            ->setParameter('letters', $possibleChars)
            // Exclure les traductions avec status_id = 1 ou 2
            ->andWhere('(s.id NOT IN (:excludedStatuses) OR s.id IS NULL)')
            ->setParameter('excludedStatuses', [1, 2])
            ->orderBy('t.' . $field, 'ASC')
            ->getQuery()
            ->getResult();
        
        // Ajouter les propriétés hasRifainSingularRecord et hasRifainPluralRecord
        foreach ($results as $key => $result) {
            $rifainSingularRecord = $result->getRifainSingularRecord();
            $rifainPluralRecord = $result->getRifainPluralRecord();
            
            // Vérifier si les flux ne sont pas nuls avant de les lire
            $results[$key]->hasRifainSingularRecord = $rifainSingularRecord && is_resource($rifainSingularRecord) && !empty(stream_get_contents($rifainSingularRecord));
            $results[$key]->hasRifainPluralRecord = $rifainPluralRecord && is_resource($rifainPluralRecord) && !empty(stream_get_contents($rifainPluralRecord));
        }
        
        return $results;
    }

    public function findAvailableLetters(string $locale): array
    {
        $field = $locale === 'en' ? 'wordEN' : 'wordFR';

        $qb = $this->createQueryBuilder('t')
            ->select('DISTINCT LOWER(SUBSTRING(TRIM(t.' . $field . '), 1, 1)) as letter')
            ->leftJoin('t.status', 's')
            ->where('t.' . $field . ' IS NOT NULL')
            ->andWhere("TRIM(t." . $field . ") != ''")
            ->andWhere('(s.id NOT IN (:excludedStatuses) OR s.id IS NULL)')
            ->setParameter('excludedStatuses', [1, 2])
            ->orderBy('letter', 'ASC');

        $result = $qb->getQuery()->getScalarResult();

        $letters = array_column($result, 'letter');

        $unaccentedLetters = [];
        foreach ($letters as $letter) {
            if ($letter) {
                // transliterate accents
                $unaccented = \Symfony\Component\String\s($letter)->ascii()->lower()->toString();
                // The result of ascii() can be multi-character, we only want the first one.
                if (!empty($unaccented) && ctype_alpha($unaccented[0]) && !in_array($unaccented[0], $unaccentedLetters)) {
                    $unaccentedLetters[] = $unaccented[0];
                }
            }
        }
        sort($unaccentedLetters);
        return $unaccentedLetters;
    }

    private function getAccentedChars(string $char): array
    {
        $map = [
            'a' => ['a', 'à', 'â', 'ä'],
            'e' => ['e', 'é', 'è', 'ê', 'ë'],
            'i' => ['i', 'î', 'ï'],
            'o' => ['o', 'ô', 'ö'],
            'u' => ['u', 'ù', 'û', 'ü'],
            'c' => ['c', 'ç'],
        ];
        return $map[strtolower($char)] ?? [strtolower($char)];
    }
}
