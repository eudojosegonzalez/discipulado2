<?php

namespace App\Controller;

use App\Entity\Aula;
use App\Form\AulaType;
use App\Repository\AulaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Knp\Component\Pager\PaginatorInterface;


#[IsGranted("ROLE_ADMIN")]
#[Route('/aula')]
final class AulaController extends AbstractController
{
    #[Route(name: 'app_aula_index', methods: ['GET'])]
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        AulaRepository $aulaRepository): Response
    {
      $appLogo=$this->getParameter('appLogo');

        $page=$request->query->getInt('page', 1);
        $limit=$request->query->getInt('limit', 10);
        
        $searchInput=$request->query->get('searchInput', "");

        if ($searchInput!="") {
            $registros = $aulaRepository->searchByNombreOrEmail($searchInput);
        } else {
            $registros = $aulaRepository->findAll();
        }

        $pagination = $paginator->paginate(
            $registros, // Objeto de la consulta a paginar
            $page, // Número de página actual
            $limit // Cantidad de elementos por página
        );     
        
    
        return $this->render('aula/index.html.twig', [
            'aulas' => $pagination,
            'appLogo' => $appLogo,
            'searchInput' => $searchInput,
            'limit' => $limit,
            'page' => $page
        ]);
    }

    #[Route('/new', name: 'app_aula_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $aula = new Aula();
        $form = $this->createForm(AulaType::class, $aula);
        $form->handleRequest($request);

        $appLogo=$this->getParameter('appLogo');
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($aula);
            $entityManager->flush();

            return $this->redirectToRoute('app_aula_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('aula/new.html.twig', [
            'aula' => $aula,
            'form' => $form,
            'appLogo' => $appLogo
        ]);
    }

    #[Route('/{id}', name: 'app_aula_show', methods: ['GET'])]
    public function show(Aula $aula): Response
    {
        $appLogo=$this->getParameter('appLogo');
        return $this->render('aula/show.html.twig', [
            'aula' => $aula,
            'appLogo' => $appLogo
        ]);
    }

    #[Route('/{id}/edit', name: 'app_aula_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Aula $aula, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AulaType::class, $aula);
        $form->handleRequest($request);
        $appLogo=$this->getParameter('appLogo');
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_aula_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('aula/edit.html.twig', [
            'aula' => $aula,
            'form' => $form,
            'appLogo' => $appLogo
        ]);
    }

    #[Route('/{id}', name: 'app_aula_delete', methods: ['POST'])]
    public function delete(Request $request, Aula $aula, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$aula->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($aula);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_aula_index', [], Response::HTTP_SEE_OTHER);
    }
}
