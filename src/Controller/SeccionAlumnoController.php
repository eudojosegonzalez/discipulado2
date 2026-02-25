<?php

namespace App\Controller;

use App\Entity\SeccionAlumno;
use App\Form\SeccionAlumnoType;
use App\Repository\DiscipuloRepository;
use App\Repository\SeccionAlumnoRepository;
use App\Repository\SeccionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

#[IsGranted("ROLE_ADMIN")]
#[Route('/seccion_alumno')]
final class SeccionAlumnoController extends AbstractController
{
    #[Route(name: 'app_seccion_alumno_index', methods: ['GET'])]
    public function index(SeccionAlumnoRepository $seccionAlumnoRepository): Response
    {
        return $this->render('seccion_alumno/index.html.twig', [
            'seccion_alumnos' => $seccionAlumnoRepository->findAll(),
        ]);
    }

    // esta funcion se encarga de buscar los discipulos de una sección
    #[Route(path: '/search_alumnos_seccion/' , name: 'app_search_alumnos_seccion', methods: ['POST'])]
    public function searchAlumnoSeccion(
        SeccionAlumnoRepository $seccionAlumnoRepository,
        Request $request        
        ): Response
    {
        $idSeccion=$request->query->getInt('idSeccion', 1);
        // buscamos los alumnos por seccion
        $registros=$seccionAlumnoRepository->searchAlumnoBySeccion($idSeccion);

        // buscamos los alumnos sin seccion
        $registrosSinSeccion=$seccionAlumnoRepository->searchAlumnosWhitOutSeccion();

        if (($registros) || ($registrosSinSeccion)) {
             $respuesta=[
                'valor'=>"1",
                "resultados"=>"se encontraro registros",
                "conSeccion"=>$registros,
                'sinSeccion'=>$registrosSinSeccion];
        } else {
             $respuesta=['valor'=>"-1", "resultados"=>"No se encontraron resultados","conSeccion"=>[],'sinSeccion'=>[]];
        }
        return new JsonResponse ($respuesta) ;
    }    

    // esta funcion private permite eliminar los registros de una sección para evitar duplicados al guardar la sección con los alumnos
    private function eliminarSeccion(EntityManagerInterface $entityManager, int $idSeccion): int
    {
        $query = $entityManager->createQueryBuilder()
            ->delete(SeccionAlumno::class, 'sap')
            ->where('sap.seccion = :idSeccion')
            ->setParameter('idSeccion', $idSeccion)
            ->getQuery();

        $numEliminados = $query->execute();
        
        return $numEliminados; // Devuelve cuántos registros se borraron
    }    

    // esta funcion se encarga de buscar los discipulos de una sección
    #[Route(path: '/save_seccion/' , name: 'app_save_seccion', methods: ['POST'])]
    public function saveSeccion(
        SeccionAlumnoRepository $seccionAlumnoRepository,
        SeccionRepository $seccionRepository,
        DiscipuloRepository $discipuloRepository,
        EntityManagerInterface $entityManager,
        Request $request        
        ): Response
    {

        $params=$request->request->all();
        $idSeccion=$params['idSeccion'];

        $arregloAlumnosTxt=$params['alumnosConSeccion'];
        
        $arregloAlumnos=json_decode($arregloAlumnosTxt, true);


        if (count($arregloAlumnos) > 0) {
            // contamos los registros de la sección para eliminar los anteriores y evitar duplicados
            $Registros = $seccionAlumnoRepository->findBy(['seccion' => $idSeccion]);
            

            // eliminamos los registros anteriores de la sección para evitar duplicados
            if ($Registros) {
                 $nRegistros = count($Registros);
            } else {
                $nRegistros = 0;
            }

            if ($nRegistros > 0) {
                $nEliminados=$this->eliminarSeccion($entityManager, $idSeccion);
            } else {
                $nEliminados = 1;
            }

            if ($nEliminados>0){
                // obtenemos la sección para verificar que existe
                $seccion=$seccionRepository->find($idSeccion);

                // recorremos el arreglo de alumnos con sección para obtener 
                // sus cédulas y guardarlos en la base de datos
                foreach ($arregloAlumnos as $cedula) {
                    // obtenemos el alumno por su cédula para verificar que existe
                    $alumno=$discipuloRepository->findOneBy(['cedula'=>$cedula]);
                    if ($alumno) {
                        $seccionAlumno = new SeccionAlumno();
                        $seccionAlumno->setSeccion($seccion);
                        $seccionAlumno->setDiscipulo($alumno);
                        $seccionAlumno->setEstado(1);
                        $seccionAlumno->setFechaCreacion(new \DateTime());
                        // hacemos persitente el registro de la sección alumno
                        $entityManager->persist($seccionAlumno);
                        $entityManager->flush();                        
                    }
                }
                $respuesta=['valor'=>"1", "resultados"=>"Discipulos agregados a la sección correctamente"];
            } else {
                $respuesta=[
                    'valor'=>"-2", 
                    "resultados"=>"No se eliminaron los registros anteriores de la sección",
                    "nRegistros" => count($Registros),
                    "nEliminados" => $nEliminados];
            }
        } else {
            $respuesta=['valor'=>"-1", "resultados"=>"No se definieron alumnos para agregar"];
        }

        return new JsonResponse (json_encode($respuesta)) ;
    }    
    
    
    // esta funcion se encarga de buscar los discipulos de una sección
    #[Route(path: '/delete_from_seccion/' , name: 'app_delete_from_seccion', methods: ['POST'])]
    public function deleteFromSeccion(
        SeccionAlumnoRepository $seccionAlumnoRepository,
        SeccionRepository $seccionRepository,
        DiscipuloRepository $discipuloRepository,
        EntityManagerInterface $entityManager,
        Request $request        
        ): Response
    {

        $params=$request->request->all();
        $idSeccion=$params['idSeccion'];

        $arregloAlumnosTxt=$params['alumnosSeleccion'];
        
        $arregloAlumnos=json_decode($arregloAlumnosTxt, true);


        if (count($arregloAlumnos) > 0) {
            // recorremos el arreglo de alumnos para eliminarlos de la seccion
            foreach ($arregloAlumnos as $cedula) {
                // obtenemos el alumno por su cédula para verificar que existe
                $alumno=$discipuloRepository->findOneBy(['cedula'=>$cedula]);
                if ($alumno) {
                    $seccionAlumno = $seccionAlumnoRepository->findOneBy(['seccion' => $idSeccion, 'discipulo' => $alumno->getId()]);
                    if ($seccionAlumno) {
                        $entityManager->remove($seccionAlumno);
                        $entityManager->flush();
                    }
                }
            }
            $respuesta=['valor'=>"1", "resultados"=>"Discipulos eliminados de la sección correctamente"];
        } else {
            $respuesta=['valor'=>"-1", "resultados"=>"No se definieron alumnos para eliminar"];
        }

        return new JsonResponse (json_encode($respuesta)) ;
    }      

    #[Route('/new', name: 'app_seccion_alumno_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $seccionAlumno = new SeccionAlumno();
        $form = $this->createForm(SeccionAlumnoType::class, $seccionAlumno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($seccionAlumno);
            $entityManager->flush();

            return $this->redirectToRoute('app_seccion_alumno_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('seccion_alumno/new.html.twig', [
            'seccion_alumno' => $seccionAlumno,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_seccion_alumno_show', methods: ['GET'])]
    public function show(SeccionAlumno $seccionAlumno): Response
    {
        return $this->render('seccion_alumno/show.html.twig', [
            'seccion_alumno' => $seccionAlumno,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_seccion_alumno_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SeccionAlumno $seccionAlumno, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SeccionAlumnoType::class, $seccionAlumno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_seccion_alumno_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('seccion_alumno/edit.html.twig', [
            'seccion_alumno' => $seccionAlumno,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_seccion_alumno_delete', methods: ['POST'])]
    public function delete(Request $request, SeccionAlumno $seccionAlumno, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$seccionAlumno->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($seccionAlumno);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_seccion_alumno_index', [], Response::HTTP_SEE_OTHER);
    }
}
