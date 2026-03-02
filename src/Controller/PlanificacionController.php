<?php

namespace App\Controller;

use App\Entity\Planificacion;
use App\Entity\DetallePlanificacion;
use App\Entity\SeccionPlanificacion;
use App\Repository\ClasesRepository;

use App\Form\PlanificacionType;
use App\Repository\PlanificacionRepository;
use App\Repository\DetallePlanificacionRepository;
use App\Repository\SeccionAlumnoRepository;
use App\Repository\SeccionPlanificacionRepository;
use App\Repository\DiscipuloRepository;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;



#[IsGranted("ROLE_ADMIN")]
#[Route('/planificacion')]
final class PlanificacionController extends AbstractController
{
    #[Route(name: 'app_planificacion_index', methods: ['GET'])]
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        PlanificacionRepository $planificacionRepository,
        ClasesRepository $clasesRepository
        ): Response
    {

        $appLogo=$this->getParameter('appLogo');

        $page=$request->query->getInt('page', 1);
        $limit=$request->query->getInt('limit', 20);
        
        $searchInput=$request->query->get('searchInput', "");

        //--- se debe crear un tipo de consulta en el repositorio 
        // para buscar por leccion, discipulador o planificador
        // ademas debe contener la cantidiad de discipulos asignados a cada planificacion
        if ($searchInput!="") {
            $registros = $planificacionRepository->searchByFecha($searchInput);
        } else {
            $registros = $planificacionRepository->findAll();
        }

        $pagination = $paginator->paginate(
            $registros, // Objeto de la consulta a paginar
            $page, // Número de página actual
            $limit // Cantidad de elementos por página
        );        
        
        $lecciones = $clasesRepository->findAll();


        // se cunenta la cantidad de discipulos asignados a cada planificacion
        $arregloPlanificaciones = [];
        foreach ($pagination as $planificacion) {
            $seccionesAsignadas = $planificacionRepository->countSeccionesAsignadas($planificacion->getId());
            $arregloPlanificaciones[] = [
                /*
                <th>Id</th>
                <th>Fecha</th>
                <th>Lección</th>
                <th>Aula</th>
                <th>Discipulador</th>
                <th>Estado</th>
                <th>Observacion</th>
                <th>Discipulos Asignados</th>
                <th>Acciones</th>
                */
                'id' => $planificacion->getId(),
                'fecha' => $planificacion->getFecha(),
                'cohorte' => $planificacion->getCohorte()->getNombre(),
                'leccion' => $planificacion->getLeccion()->getTitulo(),
                'aula' => $planificacion->getAula()->getNombre(),    
                'discipulador' => $planificacion->getUsuario()->getNombre(),
                'estado' => $planificacion->getEstado(),
                'observacion' => $planificacion->getObservacion(),
                'seccionesAsignadas' => $seccionesAsignadas,
                'leccionId' => $planificacion->getLeccion()->getId(),
                'aulaId' => $planificacion->getAula()->getId()
            ];
            
        }

        $pagination = $paginator->paginate(
            $arregloPlanificaciones, // Objeto de la consulta a paginar
            $page, // Número de página actual
            $limit // Cantidad de elementos por página
        );           

        return $this->render('planificacion/index.html.twig', [
            'planificacions' => $pagination,
            'appLogo' => $appLogo,
            'searchInput' => $searchInput,
            'limit' => $limit,
            'page' => $page,
            'lecciones' => $lecciones
        ]);
    }

    /**
     * Esta función simula el envío de notificaciones a los discípulos asignados a una planificación.
     * enviar por correo
     */
    #[Route('/send_notification/', name: 'app_planificacion_send_notification', methods: ['POST'])]    
    public function sendNotification(Request $request, PlanificacionRepository $planificacionRepository): Response
    {
        $id = $request->request->get('id');
        $leccionId = $request->request->get('leccionId');
        $aulaId = $request->request->get('aulaId');

        // debemos crear los mensajes peronalizados en la base de datos
        // debemos obtener los discipulos asignados a esta planificacion tomnando las secciones involucradas y luego buscar los discipulos asignados a esas secciones
    
        // Simulamos el envío de notificaciones
        // En una aplicación real, aquí se enviarían las notificaciones a los discípulos
        // Por ahora, solo devolvemos un valor de éxito
        return new Response(json_encode(['valor' => 1]));
    }



    #[Route('/new', name: 'app_planificacion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $planificacion = new Planificacion();
        $form = $this->createForm(PlanificacionType::class, $planificacion);
        $form->handleRequest($request);

        $appLogo=$this->getParameter('appLogo');

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($planificacion);
            $entityManager->flush();

            return $this->redirectToRoute('app_planificacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planificacion/new.html.twig', [
            'planificacion' => $planificacion,
            'form' => $form,
            'appLogo' => $appLogo
        ]);
    }

    #[Route('/{id}/subir_leccion', name: 'app_planificacion_subir_leccion', methods: ['GET'])]
    public function subirLeccion(
        Planificacion $planificacion, // Symfony lo busca automáticamente por el {id} del path
        Request $request
    ): Response {
        $appLogo = $this->getParameter('appLogo');

        // Estos vienen de la URL: ?leccionId=X&aulaId=Y
        $leccionId = $request->query->get('leccionId');
        $aulaId = $request->query->get('aulaId');

        return $this->render('planificacion/subir_leccion.html.twig', [
            'planificacion' => $planificacion,
            'leccionId' => $leccionId,
            'aulaId' => $aulaId,
            'appLogo' => $appLogo
        ]);
    }  

    #[Route('/{id}/asociar_discipulos', name: 'app_planificacion_asociar_discipulos', methods: ['GET'])]
    public function asociarDiscipulos(
        Planificacion $planificacion, // Symfony lo busca automáticamente por el {id} del path
        DiscipuloRepository $discipuloRepository,
        DetallePlanificacionRepository $detallePlanificacionRepository,
        SeccionAlumnoRepository $seccionAlumnoRepository,
        PlanificacionRepository $planificacionRepository,
        Request $request
    ): Response {
        $appLogo = $this->getParameter('appLogo');

       
        // determinamos la cohorte actual
        $cohorteActual=$planificacion->getCohorte();
        // determinamos el id de la cohorte actual
        $idCohorte=$cohorteActual->getId();
        // buscamos las secciones no asignadas a ninguna planificacion
        $seccionesNoAsignadas=$planificacionRepository->searchSeccionesNoAsignadas($idCohorte);

        // buscamos las secciones asignados a esta planificacion
        $seccionesAsignadas=$planificacionRepository->searchSeccionesAsignadas($idCohorte);

        return $this->render('planificacion/asociar_discipulos.html.twig', [
            'planificacion' => $planificacion,
            'seccionesNoAsignadas' => $seccionesNoAsignadas,
            'seccionesAsignadas' => $seccionesAsignadas,
            'appLogo' => $appLogo
        ]);
    }  

    #[Route('/{id}', name: 'app_planificacion_show', methods: ['GET'])]
    public function show(Planificacion $planificacion): Response
    {
        $appLogo=$this->getParameter('appLogo');
        return $this->render('planificacion/show.html.twig', [
            'planificacion' => $planificacion,
            'appLogo' => $appLogo
        ]);
    }

    #[Route('/{id}/edit', name: 'app_planificacion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Planificacion $planificacion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlanificacionType::class, $planificacion);
        $form->handleRequest($request);

        $appLogo=$this->getParameter('appLogo');
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_planificacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planificacion/edit.html.twig', [
            'planificacion' => $planificacion,
            'form' => $form,
            'appLogo' => $appLogo
        ]);
    }

    #[Route('/{id}', name: 'app_planificacion_delete', methods: ['POST'])]
    public function delete(Request $request, Planificacion $planificacion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planificacion->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($planificacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_planificacion_index', [], Response::HTTP_SEE_OTHER);
    }



}