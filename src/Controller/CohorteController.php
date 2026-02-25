<?php

namespace App\Controller;

use App\Entity\Cohorte;
use App\Form\CohorteType;
use App\Repository\CohorteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

#[IsGranted("ROLE_ADMIN")]
#[Route('/cohorte')]
final class CohorteController extends AbstractController
{
    #[Route(name: 'app_cohorte_index', methods: ['GET'])]
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        CohorteRepository $cohorteRepository): Response
    {
        $appLogo=$this->getParameter('appLogo');

        $page=$request->query->getInt('page', 1);
        $limit=$request->query->getInt('limit', 20);
        
        $searchInput=$request->query->get('searchInput', "");

        if ($searchInput!="") {
            $registros = $cohorteRepository->searchByNombre($searchInput);
        } else {
            $registros = $cohorteRepository->findAll();
        }

        $pagination = $paginator->paginate(
            $registros, // Objeto de la consulta a paginar
            $page, // Número de página actual
            $limit // Cantidad de elementos por página
        );     
        
    
        return $this->render('cohorte/index.html.twig', [
            'cohortes' => $pagination,
            'appLogo' => $appLogo,
            'searchInput' => $searchInput,
            'limit' => $limit,
            'page' => $page
        ]);
    }

    #[Route('/new', name: 'app_cohorte_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cohorte = new Cohorte();
        $form = $this->createForm(CohorteType::class, $cohorte);
        $form->handleRequest($request);
        $appLogo = $this->getParameter('appLogo');

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cohorte);
            $entityManager->flush();

            return $this->redirectToRoute('app_cohorte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cohorte/new.html.twig', [
            'cohorte' => $cohorte,
            'form' => $form,
            'appLogo' => $appLogo,
        ]);
    }

    #[Route('/{id}', name: 'app_cohorte_show', methods: ['GET'])]
    public function show(Cohorte $cohorte): Response
    {
        $appLogo = $this->getParameter('appLogo');
        return $this->render('cohorte/show.html.twig', [
            'cohorte' => $cohorte,
            'appLogo' => $appLogo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cohorte_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cohorte $cohorte, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CohorteType::class, $cohorte);
        $form->handleRequest($request);
        $appLogo = $this->getParameter('appLogo');

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cohorte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cohorte/edit.html.twig', [
            'cohorte' => $cohorte,
            'form' => $form,
            'appLogo' => $appLogo,
        ]);
    }

    #[Route('/{id}', name: 'app_cohorte_delete', methods: ['POST'])]
    public function delete(Request $request, Cohorte $cohorte, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cohorte->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cohorte);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cohorte_index', [], Response::HTTP_SEE_OTHER);
    }
}
