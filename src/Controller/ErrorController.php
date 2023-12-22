<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ErrorController extends AbstractController
{

    #[Route(path:'/404', name:'error_404')]
    public function error404(): Response
    {
        return $this->render('error/error404.html.twig');
    }

    public function error(Request $request): Response
    {
        // Capture l'exception de Symfony
        $exception = $request->get('exception');

        // Personnalisez le template selon le type d'exception
        $template = 'error/error.html.twig';

        // Si c'est une exception d'accès refusé
        if ($exception instanceof AccessDeniedException || $exception->getPrevious() instanceof AccessDeniedException) {
            $template = 'error/error403.html.twig';
        }

        // Renvoie la réponse
        return $this->render($template, [
            'error' => $exception->getMessage(),
        ]);
    }
}