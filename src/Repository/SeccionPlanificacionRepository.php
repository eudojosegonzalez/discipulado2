<?php

namespace App\Repository;

use App\Entity\SeccionPlanificacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SeccionPlanificacion>
 */
class SeccionPlanificacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SeccionPlanificacion::class);
    }

    //    /**
    //     * @return SeccionPlanificacion[] Returns an array of SeccionPlanificacion objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SeccionPlanificacion
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    function searchSeccionesNoAsignadas(int $cohorteId): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'select * from discipulado2.seccion 
        where id not in 
        (SELECT id FROM discipulado2.vista_secciones_planificacion
        where cohorte_id=:cohorteId)
        ';
        
        $stmt = $conn->prepare($sql);
        // Asignamos el valor explícitamente al placeholder
        $stmt->bindValue('cohorteId', $cohorteId);
        
        $resultSet = $stmt->executeQuery();
        $result = $resultSet->fetchAssociative();

        return $result;
    }   
    
    function searchSeccionesAsignadas(int $cohorteId): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'select * from discipulado2.seccion 
        where id in 
        (SELECT id FROM discipulado2.vista_secciones_planificacion
        where cohorte_id=:cohorteId)
        ';
        
        $stmt = $conn->prepare($sql);
        // Asignamos el valor explícitamente al placeholder
        $stmt->bindValue('cohorteId', $cohorteId);
        
        $resultSet = $stmt->executeQuery();
        $result = $resultSet->fetchAssociative();

        return $result;
    }  
    

}
