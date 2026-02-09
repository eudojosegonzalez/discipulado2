<?php

namespace App\Controller;

use App\Entity\Clases;
use App\Form\ClasesType;
use App\Repository\ClasesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_ADMIN")]
#[Route('/clases')]
final class ClasesController extends AbstractController
{
    #[Route(name: 'app_clases_index', methods: ['GET'])]
    public function index(ClasesRepository $clasesRepository): Response
    {
        $appLogo=$this->getParameter('appLogo');
        return $this->render('clases/index.html.twig', [
            'clases' => $clasesRepository->findAll(),
            'appLogo' => $appLogo
        ]);
    }

    #[Route('/new', name: 'app_clases_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $appLogo=$this->getParameter('appLogo');
        $clase = new Clases();
        $form = $this->createForm(ClasesType::class, $clase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($clase);
            $entityManager->flush();

            return $this->redirectToRoute('app_clases_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('clases/new.html.twig', [
            'clase' => $clase,
            'form' => $form,
            'appLogo' => $appLogo
        ]);
    }

    #[Route('/{id}', name: 'app_clases_show', methods: ['GET'])]
    public function show(Clases $clase): Response
    {
        $appLogo=$this->getParameter('appLogo');
        return $this->render('clases/show.html.twig', [
            'clase' => $clase,
            'appLogo' => $appLogo
        ]);
    }

    #[Route('/{id}/edit', name: 'app_clases_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Clases $clase, EntityManagerInterface $entityManager): Response
    {
        $appLogo=$this->getParameter('appLogo');
        $form = $this->createForm(ClasesType::class, $clase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_clases_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('clases/edit.html.twig', [
            'clase' => $clase,
            'form' => $form,
            'appLogo' => $appLogo
        ]);
    }

    #[Route('/{id}', name: 'app_clases_delete', methods: ['POST'])]
    public function delete(Request $request, Clases $clase, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clase->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($clase);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_clases_index', [], Response::HTTP_SEE_OTHER);
    }
}
