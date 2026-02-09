<?php

namespace App\Repository;

use App\Entity\Planificacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Planificacion>
 */
class PlanificacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planificacion::class);
    }

    //    /**
    //     * @return Planificacion[] Returns an array of Planificacion objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Planificacion
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    function countDiscipulosAsignados(int $planificacionId): int
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT COUNT(id) AS total
            FROM detalle_planificacion dp
            WHERE dp.planificacion_id = :planificacionId
        ';
        
        $stmt = $conn->prepare($sql);
        // Asignamos el valor explícitamente al placeholder
        $stmt->bindValue('planificacionId', $planificacionId);
        
        $resultSet = $stmt->executeQuery();
        
        // Como es un COUNT, probablemente solo quieras el número, no un array de arrays
        $result = $resultSet->fetchAssociative();
        return $result['total'] ?? 0;
    }
}
