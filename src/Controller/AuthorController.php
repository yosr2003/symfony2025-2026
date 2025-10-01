<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
   #[Route('/author/{name}', name: 'show_author')] 
public function showAuthor(string $name): Response
{
    return $this->render('author_controllerr/show.html.twig', [ 
        'name' => $name,
    ]);
}

#[Route('/authors', name: 'list_authors')]
public function listAuthors(): Response
{
    $authors = [
        ['id' => 1, 'picture' => 'assets/images/logo1.png','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
        ['id' => 2, 'picture' => 'assets/images/logo2.png','username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
        ['id' => 3, 'picture' => 'assets/images/logo3.png','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
    ];

    return $this->render('author_controllerr/list.html.twig', [
        'authors' => $authors,
    ]);
}


}
