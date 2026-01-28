<?php

namespace App\Repository;

use DateTimeImmutable;
use App\Entity\NewsletterSentWords;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<NewsletterSentWords>
 *
 * @method NewsletterSentWords|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsletterSentWords|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsletterSentWords[]    findAll()
 * @method NewsletterSentWords[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsletterSentWordsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsletterSentWords::class);
    }


    public function findSentWordIds(): array
    {
        return $this->createQueryBuilder('nsw')
            ->select('nsw.word_id')
            ->getQuery()
            ->getSingleColumnResult(); // Utilisez getSingleColumnResult pour retourner un tableau simple d'IDs
    }

    public function saveSentWordIds(array $wordIds): void
    {
        $em = $this->getEntityManager();
    
        foreach ($wordIds as $wordId) {
            $sentWord = new NewsletterSentWords();
            $sentWord->setWordId($wordId);
            $sentWord->setSentAt(new \DateTimeImmutable());
            $em->persist($sentWord);
        }
    
        $em->flush();
    }
//    /**
//     * @return NewsletterSentWords[] Returns an array of NewsletterSentWords objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NewsletterSentWords
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
