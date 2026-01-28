<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }


    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllArchived()
    {
        return $this->createQueryBuilder('u') // Première lettre de l'entité (article) / Comme si on faisait un # SELECT * FROM category
            ->where('u.deletedAt IS NOT NULL') # WHERE deleted_at IS NOT NULL
            ->getQuery() # Permet de récupérer la requête SQL
            ->getResult() # Permet de récupérer els résultats de la requête
        ;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findUnverifiedUsersWithoutReminder()
    {
        return $this->createQueryBuilder('u')
            ->where('u.isVerified = :verified')
            ->andWhere('u.reminderSentAt IS NULL')
            ->setParameter('verified', false)
            ->getQuery()
            ->getResult();
    }

    public function findExpiredUnverifiedUsers(\DateTime $weekAgo)
    {
        return $this->createQueryBuilder('u')
            ->where('u.isVerified = :verified')
            ->andWhere('u.reminderSentAt <= :weekAgo')
            ->setParameter('verified', false)
            ->setParameter('weekAgo', $weekAgo)
            ->getQuery()
            ->getResult();
    }

    public function findUnverifiedUsersWithLimitAndOffset(int $limit, int $offset)
    {
        return $this->createQueryBuilder('u')
            ->where('u.isVerified = false')
            ->andWhere('u.reminderSentAt IS NULL')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

  public function findByRoles(array $roles): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :adminRole')
            ->orWhere('u.roles LIKE :moderatorRole')
            ->setParameter('adminRole', '%"ROLE_ADMIN"%')
            ->setParameter('moderatorRole', '%"ROLE_MODERATOR"%')
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
