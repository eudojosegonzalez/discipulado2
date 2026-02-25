<?php

namespace App\Repository;

use App\Entity\SeccionAlumno;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SeccionAlumno>
 */
class SeccionAlumnoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SeccionAlumno::class);
    }

    //    /**
    //     * @return SeccionAlumno[] Returns an array of SeccionAlumno objects
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

    //    public function findOneBySomeField($value): ?SeccionAlumno
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    function searchAlumnoBySeccion(int $idSeccion): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select *  FROM discipulado2.view_alumnos_secciones where seccion_id ='.$idSeccion.'  order by apellido ASC';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        
        return $resultSet->fetchAllAssociative();        
    }

    // buscamos los alumnos que no tienen seccion
    function searchAlumnosWhitOutSeccion(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'select  
            discipulo.cedula,
            discipulo.nombre,
            discipulo.apellido,
            discipulo.fecha_nac,
            discipulo.sexo,
            discipulo.telefono,
            discipulo.email,
            discipulo.estado
            FROM discipulado2.discipulo
            where discipulo.id  not in (select discipulo_id from discipulado2.seccion_alumno)
            order by discipulo.apellido,discipulo.nombre asc';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        
        return $resultSet->fetchAllAssociative();        
    }


}
