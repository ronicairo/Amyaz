<?php

namespace App\Repository;

use App\Entity\GrammarSheet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GrammarSheet>
 *
 * @method GrammarSheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrammarSheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrammarSheet[]    findAll()
 * @method GrammarSheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrammarSheetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GrammarSheet::class);
    }

    //    /**
    //     * @return GrammarSheet[] Returns an array of GrammarSheet objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?GrammarSheet
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
