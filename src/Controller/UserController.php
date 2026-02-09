<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Knp\Component\Pager\PaginatorInterface;


#[IsGranted("ROLE_ADMIN")]
#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(
        PaginatorInterface $paginator,
        Request $request,
        UserRepository $userRepository): Response
    {
        $appLogo=$this->getParameter('appLogo');        


        $page=$request->query->getInt('page', 1);
        $limit=$request->query->getInt('limit', 10);
        
        $searchInput=$request->query->get('searchInput', "");

        if ($searchInput!="") {
            $registros = $userRepository->searchByNombreOrEmail($searchInput);
        } else {
            $registros = $userRepository->findAll();
        }

        $pagination = $paginator->paginate(
            $registros, // Objeto de la consulta a paginar
            $page, // Número de página actual
            $limit // Cantidad de elementos por página
        );     
        
        
        return $this->render('user/index.html.twig', [
            'users' => $pagination,
            'appLogo' => $appLogo,
            'searchInput' => $searchInput,

        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $appLogo=$this->getParameter('appLogo');
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'appLogo' => $appLogo
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        $appLogo=$this->getParameter('appLogo');
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'appLogo' => $appLogo
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $appLogo=$this->getParameter('appLogo');
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'appLogo' => $appLogo
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
