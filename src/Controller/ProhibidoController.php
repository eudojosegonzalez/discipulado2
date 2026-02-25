<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProhibidoController extends AbstractController
{
    #[Route('/prohibido', name: 'app_prohibido')]
    public function index(): Response
    {
        return $this->render('prohibido/index.html.twig', [
            'controller_name' => 'ProhibidoController',
        ]);
    }
}
