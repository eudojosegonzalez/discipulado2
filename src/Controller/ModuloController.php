<?php

namespace App\Controller;

use App\Entity\Modulo;
use App\Form\ModuloType;
use App\Repository\ModuloRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_ADMIN")]
#[Route('/modulo')]
final class ModuloController extends AbstractController
{
    #[Route(name: 'app_modulo_index', methods: ['GET'])]
    public function index(ModuloRepository $moduloRepository): Response
    {
        $appLogo=$this->getParameter('appLogo');
        return $this->render('modulo/index.html.twig', [
            'modulos' => $moduloRepository->findAll(),
            'appLogo' => $appLogo
        ]);
    }

    #[Route('/new', name: 'app_modulo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $appLogo=$this->getParameter('appLogo');
        $modulo = new Modulo();
        $form = $this->createForm(ModuloType::class, $modulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($modulo);
            $entityManager->flush();

            return $this->redirectToRoute('app_modulo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('modulo/new.html.twig', [
            'modulo' => $modulo,
            'form' => $form,
            'appLogo' => $appLogo
        ]);
    }

    #[Route('/{id}', name: 'app_modulo_show', methods: ['GET'])]
    public function show(Modulo $modulo): Response
    {
        $appLogo=$this->getParameter('appLogo');
        return $this->render('modulo/show.html.twig', [
            'modulo' => $modulo,
            'appLogo' => $appLogo
        ]);
    }

    #[Route('/{id}/edit', name: 'app_modulo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Modulo $modulo, EntityManagerInterface $entityManager): Response
    {
        
        $appLogo=$this->getParameter('appLogo');
        $form = $this->createForm(ModuloType::class, $modulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_modulo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('modulo/edit.html.twig', [
            'modulo' => $modulo,
            'form' => $form,
            'appLogo' => $appLogo
        ]);
    }

    #[Route('/{id}', name: 'app_modulo_delete', methods: ['POST'])]
    public function delete(Request $request, Modulo $modulo, EntityManagerInterface $entityManager): Response
    {
        
        if ($this->isCsrfTokenValid('delete'.$modulo->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($modulo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_modulo_index', [], Response::HTTP_SEE_OTHER);
    }
}
