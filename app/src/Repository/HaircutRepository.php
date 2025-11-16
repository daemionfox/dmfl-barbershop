<?php

namespace App\Repository;

use App\Entity\Haircut;
use App\Enumerations\StatusEnumeration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Haircut>
 */
class HaircutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Haircut::class);
    }

    public function findCurrentActiveHaircuts($cadetbadge): array
    {
        $qb = $this->createQueryBuilder('h');
        $qb->where('h.cadetbadge = :cb')
            ->andWhere(
                $qb->expr()->in('h.status', [StatusEnumeration::STATUS_ACTIVE, StatusEnumeration::STATUS_WAITING, StatusEnumeration::STATUS_STARTED])
            )
            ->setParameter('cb', $cadetbadge)
            ->orderBy('h.starttime', 'ASC');
        return $qb->getQuery()->getResult();
    }

    public function findPendingHaircuts(): array
    {
        $qb = $this->createQueryBuilder('h');
        $qb->where(
                $qb->expr()->in('h.status', [StatusEnumeration::STATUS_ACTIVE, StatusEnumeration::STATUS_WAITING, StatusEnumeration::STATUS_STARTED])
            )
            ->orderBy('h.starttime', 'ASC');
        return $qb->getQuery()->getResult();
    }



    //    /**
    //     * @return Haircut[] Returns an array of Haircut objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('h.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Haircut
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
