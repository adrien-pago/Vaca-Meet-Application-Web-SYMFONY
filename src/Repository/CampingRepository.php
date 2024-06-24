<?php

namespace App\Repository;

use App\Entity\Camping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Camping>
 * @implements PasswordUpgraderInterface<Camping>
 *
 * @method Camping|null find($id, $lockMode = null, $lockVersion = null)
 * @method Camping|null findOneBy(array $criteria, array $orderBy = null)
 * @method Camping[]    findAll()
 * @method Camping[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampingRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Camping::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $camping, string $newHashedPassword): void
    {
        if (!$camping instanceof Camping) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $camping::class));
        }

        $camping->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($camping);
        $this->getEntityManager()->flush();
    }

    // Méthodes personnalisées pour votre entité Camping peuvent être ajoutées ici

//    /**
//     * @return Camping[] Returns an array of Camping objects
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

//    public function findOneBySomeField($value): ?Camping
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
