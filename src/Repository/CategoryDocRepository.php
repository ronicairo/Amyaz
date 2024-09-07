<?php

namespace App\Repository;

use App\Entity\CategoryDoc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryDoc>
 *
 * @method CategoryDoc|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryDoc|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryDoc[]    findAll()
 * @method CategoryDoc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryDocRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryDoc::class);
    }

    public function save(CategoryDoc $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return CategoryDoc[] Returns an array of CategoryDoc objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CategoryDoc
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
