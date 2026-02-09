<?php

namespace App\Controller;

use App\Entity\Planificacion;
use App\Form\PlanificacionType;
use App\Repository\PlanificacionRepository;
use App\Repository\ClasesRepository;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Knp\Component\Pager\PaginatorInterface;


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
        $limit=$request->query->getInt('limit', 10);
        
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
            $discipulosAsignados = $planificacionRepository->countDiscipulosAsignados($planificacion->getId());
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
                'leccion' => $planificacion->getLeccion()->getTitulo(),
                'aula' => $planificacion->getAula()->getNombre(),    
                'discipulador' => $planificacion->getUsuario()->getNombre(),
                'estado' => $planificacion->getEstado(),
                'observacion' => $planificacion->getObservacion(),
                'discipulosAsignados' => $discipulosAsignados
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
