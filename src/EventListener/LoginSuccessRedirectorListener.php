<?php

namespace App\EventListener;

use App\Entity\User; // Asegúrate de que esta sea la ruta correcta a tu entidad User
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

final class LoginSuccessRedirectorListener
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
    
    
    #[AsEventListener(event: LoginSuccessEvent::class)]
    public function onLoginSuccessEvent(LoginSuccessEvent $event): void
    {

        // Obtiene el objeto User que acaba de iniciar sesión
        $user = $event->getUser();

        // Si el objeto no es una instancia de tu entidad User, salimos
        if (!$user instanceof User) {
            return;
        }

        $targetPath = null;

        # verificamos el estado del usuario si está o no activo

        if ($user->getEstado() != 1){
            # no esta activo desviamos a la pagina de prohivido
            $targetPath = $this->urlGenerator->generate('app_prohibido');
            $response = new RedirectResponse($targetPath);
            $event->setResponse($response); // Establece la respuesta de redirección en el evento
            return;
        }
        // esta activo redirigimos al dashboard correspondiente
        // Determinamos la ruta de destino según el nivel del usuario
        $targetPath = null;
        switch ($user->getNivel()) {
            case 99:
                $targetPath = $this->urlGenerator->generate('app_dashboard_admin');
                break;
            case 50:
                // discipulador
                $targetPath = $this->urlGenerator->generate('app_dashboard_admin');
                break;
            case 60:
                // gestor de discipulados
                $targetPath = $this->urlGenerator->generate('app_dashboard_admin');
                break;    
            default:
                // Ruta por defecto si el nivel no coincide con ninguno de los definidos
                // Puedes cambiar 'app_home' por cualquier otra ruta por defecto
                $targetPath = $this->urlGenerator->generate('app_home');
                break;
        }

        // Si se encontró una ruta de destino, redirigimos
        if ($targetPath) {
            $response = new RedirectResponse($targetPath);
            $event->setResponse($response); // Establece la respuesta de redirección en el evento
        }
    }
}
