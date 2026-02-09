<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted("ROLE_ADMIN")]
final class DashboardAdminController extends AbstractController
{
    #[Route('/dashboard_admin', name: 'app_dashboard_admin')]
    public function index(): Response
    {
        $user=$this->getUser();
        $appLogo=$this->getParameter('appLogo');

        return $this->render('dashboard_admin/index.html.twig', [
            'controller_name' => 'DashboardAdminController',
            'appLogo'=>$appLogo,
            'user'=>$user,
        ]);
    }
}
