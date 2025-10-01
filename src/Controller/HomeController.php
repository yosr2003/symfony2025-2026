<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'home_page')] 
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'identifiant' => 5,
        ]);
    }

    #[Route('/hello', name: 'hello')]
    public function hello(): Response
    {
        return new Response("Hello 3a26"); 
    }

    #[Route('/contact/{tel}', name: 'contact')]
    public function contact(string $tel): Response
    {
        return $this->render('home/contact.html.twig', [
            'telephone' => $tel,
        ]);
    }

    #[Route('/show', name: 'show')]
    public function show(): Response
    {
        return new Response("bienvenue à Ooredoo");
    }

    #[Route('/affiche', name: 'affiche')]
    public function affiche(): Response
    {
        return $this->render('home/apropos.html.twig');
    }

    #[Route('/', name: 'homepage')]
    public function rediriger(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Redirection depuis /',
            'identifiant' => 999,
        ]);
    }
}
