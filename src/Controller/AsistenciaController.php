<?php

namespace App\Controller;

use App\Entity\Asistencia;
use App\Form\AsistenciaType;
use App\Repository\AsistenciaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/asistencia')]
final class AsistenciaController extends AbstractController
{
    #[Route(name: 'app_asistencia_index', methods: ['GET'])]
    public function index(AsistenciaRepository $asistenciaRepository): Response
    {
        return $this->render('asistencia/index.html.twig', [
            'asistencias' => $asistenciaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_asistencia_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $asistencium = new Asistencia();
        $form = $this->createForm(AsistenciaType::class, $asistencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($asistencium);
            $entityManager->flush();

            return $this->redirectToRoute('app_asistencia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('asistencia/new.html.twig', [
            'asistencium' => $asistencium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_asistencia_show', methods: ['GET'])]
    public function show(Asistencia $asistencium): Response
    {
        return $this->render('asistencia/show.html.twig', [
            'asistencium' => $asistencium,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_asistencia_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Asistencia $asistencium, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AsistenciaType::class, $asistencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_asistencia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('asistencia/edit.html.twig', [
            'asistencium' => $asistencium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_asistencia_delete', methods: ['POST'])]
    public function delete(Request $request, Asistencia $asistencium, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$asistencium->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($asistencium);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_asistencia_index', [], Response::HTTP_SEE_OTHER);
    }
}
