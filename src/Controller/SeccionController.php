<?php

namespace App\Controller;

use App\Entity\Seccion;
use App\Form\SeccionType;
use App\Repository\SeccionRepository;
use App\Repository\SeccionAlumnoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

#[IsGranted("ROLE_ADMIN")]
#[Route('/seccion')]
final class SeccionController extends AbstractController
{
    #[Route(name: 'app_seccion_index', methods: ['GET'])]
    public function index(
        SeccionRepository $seccionRepository, 
        SeccionAlumnoRepository $seccionAlumnoRepository,
        PaginatorInterface $paginator, 
        Request $request): Response
    {
        $appLogo=$this->getParameter('appLogo');

        $page=$request->query->getInt('page', 1);
        $limit=$request->query->getInt('limit', 20);
        
        $searchInput=$request->query->get('searchInput', "");        

        if ($searchInput!="") {
            $registros = $seccionRepository->searchByNombre($searchInput);
        } else {
            $registros = $seccionRepository->findBy([], [
                'nombre' => 'ASC'
            ]);
        }

        // buscamos el número de discipulos por sección
        $registrosSalida=[];
        foreach ($registros as $seccion) {
            $nDiscipulos = $seccionAlumnoRepository->countBySeccion($seccion->getId());
            $registrosSalida[]=[
                'id' => $seccion->getId(),
                'nombre' => $seccion->getNombre(),
                'cohorte' => $seccion->getCohorte(),
                'fechaCreacion' => $seccion->getFechaCreacion(),
                'estado' => $seccion->getEstado(),          
                'nDiscipulos' => $nDiscipulos,
            ];
            
        }   

        $seccions = $paginator->paginate(
            $registrosSalida,
            $request->query->getInt('page', 1),
            10
        );        

        return $this->render('seccion/index.html.twig', [
            'seccions' => $seccions,
            'appLogo' => $appLogo,
            'searchInput' => $searchInput,
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    #[Route('/new', name: 'app_seccion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $seccion = new Seccion();
        $form = $this->createForm(SeccionType::class, $seccion);
        $form->handleRequest($request);
        $appLogo=$this->getParameter('appLogo');
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($seccion);
            $entityManager->flush();

            return $this->redirectToRoute('app_seccion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('seccion/new.html.twig', [
            'seccion' => $seccion,
            'form' => $form,
            'appLogo' => $appLogo,
        ]);
    }

    #[Route('/{id}', name: 'app_seccion_show', methods: ['GET'])]
    public function show(Seccion $seccion): Response
    {
        $appLogo=$this->getParameter('appLogo');
        return $this->render('seccion/show.html.twig', [
            'seccion' => $seccion,
            'appLogo' => $appLogo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_seccion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Seccion $seccion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SeccionType::class, $seccion);
        $form->handleRequest($request);
        $appLogo=$this->getParameter('appLogo');
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_seccion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('seccion/edit.html.twig', [
            'seccion' => $seccion,
            'form' => $form,
            'appLogo' => $appLogo,
        ]);
    }

    #[Route('/{id}', name: 'app_seccion_delete', methods: ['POST'])]
    public function delete(Request $request, Seccion $seccion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$seccion->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($seccion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_seccion_index', [], Response::HTTP_SEE_OTHER);
    }
}
