<?php

namespace App\Controller;

use App\Entity\Discipulo;
use App\Form\DiscipuloType;
use App\Repository\DiscipuloRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Knp\Component\Pager\PaginatorInterface;


#[IsGranted("ROLE_ADMIN")]
#[Route('/discipulo')]
final class DiscipuloController extends AbstractController
{
    #[Route(name: 'app_discipulo_index', methods: ['GET'])]
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        DiscipuloRepository $discipuloRepository): Response
    {
        $appLogo=$this->getParameter('appLogo');

        $page=$request->query->getInt('page', 1);
        $limit=$request->query->getInt('limit', 25);
        
        $searchInput=$request->query->get('searchInput', "");

        $Orden=$request->query->get('Orden', "");

        // determinamos la cantidad de registros en el sistema
        $registros = $discipuloRepository->findAll();
        $nDiscipulos = count($registros);

        // determinamos la cantidad de hombres y mujeres
        $nHombres = $discipuloRepository->countBySexo('1');
        $nMujeres = $discipuloRepository->countBySexo('0');

        if (($Orden!="") && ($Orden!="-99")) {
            $arregloORden = explode("_", $Orden);
            $campoOrden = $arregloORden[0];
            $tipoOrden = $arregloORden[1];
        } else {
            $campoOrden = "";
            $tipoOrden = "";
        }

        $caso=0;
        if ($searchInput!="") {
            $caso=1;
            if (($Orden!="") && ($Orden!="-99")) {
                $caso=2;
                $registros = $discipuloRepository->findBy(['nombre'=>$searchInput], [$campoOrden => $tipoOrden]);
            } else {
                $caso=3;
                $registros = $discipuloRepository->findBySearchInput($searchInput);
            }
        } else {
            // no hay criterio de búsqueda, se muestran todos los registros
            $caso=4;
            if (($Orden!="") && ($Orden!="-99")) {
                $caso=5;
                $registros = $discipuloRepository->findBy([], [$campoOrden => $tipoOrden]);
            } else {
                $caso=6;
                $registros = $discipuloRepository->findBy([], [
                    'apellido' => 'ASC',
                    'nombre' => 'ASC'
                ]);
            }
        }

        $pagination = $paginator->paginate(
            $registros, // Objeto de la consulta a paginar
            $page, // Número de página actual
            $limit // Cantidad de elementos por página
        );     

        $nBusqueda = count($registros);
        
        
        return $this->render('discipulo/index.html.twig', [
            'discipulos' => $pagination,
            'appLogo' => $appLogo,
            'searchInput' => $searchInput,
            'limit' => $limit,
            'page' => $page,
            'nDiscipulos' => $nDiscipulos,
            'nHombres' => $nHombres,
            'nMujeres' => $nMujeres,
            'nBusqueda' => $nBusqueda,
            'Orden'=>$Orden
        ]);
    }

    #[Route('/new', name: 'app_discipulo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $discipulo = new Discipulo();
        $form = $this->createForm(DiscipuloType::class, $discipulo);
        $form->handleRequest($request);
        $appLogo=$this->getParameter('appLogo');
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($discipulo);
            $entityManager->flush();

            return $this->redirectToRoute('app_discipulo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('discipulo/new.html.twig', [
            'discipulo' => $discipulo,
            'form' => $form,
            'appLogo' => $appLogo,
        ]);
    }

    #[Route('/{id}', name: 'app_discipulo_show', methods: ['GET'])]
    public function show(Discipulo $discipulo): Response
    {
        $appLogo=$this->getParameter('appLogo');
        return $this->render('discipulo/show.html.twig', [
            'discipulo' => $discipulo,
            'appLogo' => $appLogo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_discipulo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Discipulo $discipulo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DiscipuloType::class, $discipulo);
        $form->handleRequest($request);
        $appLogo=$this->getParameter('appLogo');
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_discipulo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('discipulo/edit.html.twig', [
            'discipulo' => $discipulo,
            'form' => $form,
            'appLogo' => $appLogo
        ]);
    }

    #[Route('/{id}', name: 'app_discipulo_delete', methods: ['POST'])]
    public function delete(Request $request, Discipulo $discipulo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$discipulo->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($discipulo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_discipulo_index', [], Response::HTTP_SEE_OTHER);
    }
}
