<?php

namespace App\Repository;

use App\Entity\Discipulo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Discipulo>
 */
class DiscipuloRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Discipulo::class);
    }

    //    /**
    //     * @return Discipulo[] Returns an array of Discipulo objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Discipulo
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    function searchByNombreOrEmail($searchInput)
    {
        $qb = $this->createQueryBuilder('d');
        $qb->where($qb->expr()->orX(
            $qb->expr()->like('d.nombre', ':searchInput'),
            $qb->expr()->like('d.email', ':searchInput')
        ))
        ->setParameter('searchInput', '%' . $searchInput . '%')
        ->orderBy('d.apellido', 'ASC')
        ->addOrderBy('d.nombre', 'ASC');        

        return $qb->getQuery()->getResult();
    }
}
