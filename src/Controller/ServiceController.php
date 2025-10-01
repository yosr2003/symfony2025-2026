<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }

    #[Route('/service/{name}', name: 'show_service')]
    public function showService(string $name): Response
    {
        return $this->render('service/show.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/goToIndex', name: 'goToIndex')]
    public function goToIndex(): Response
    {
        // Option 1 : Afficher directement la vue avec les variables nécessaires
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'identifiant' => 999, // Ajout de la variable manquante
        ]);

        // Option 2 : Redirection vers la route app_home
        // return $this->redirectToRoute('app_home');
    }
}
