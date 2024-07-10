<?php

namespace App\Repository;

use App\Entity\Planning;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Planning|null find($id, $lockMode = null, $lockVersion = null)
 * @method Planning|null findOneBy(array $criteria, array $orderBy = null)
 * @method Planning[]    findAll()
 * @method Planning[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planning::class);
    }

    public function findByDateRange(DateTime $startDate, DateTime $endDate): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.dateDebut BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->getQuery()
            ->getResult();
    }

    // Ajoutez vos méthodes de requête personnalisées ici
}

