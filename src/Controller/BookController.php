<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;  
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
#[Route('/book/add', name: 'add_book')]
public function addBook(Request $request, EntityManagerInterface $em): Response
{
    $book = new Book();
    $form = $this->createForm(BookType::class, $book);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
  
        $book->setPublished(true);

     
        $author = $book->getUser();
        if ($author->getNbBooks() !== null) {
            $author->setNbBooks($author->getNbBooks() + 1);
        } else {
            $author->setNbBooks(1);
        }

        $em->persist($book);
        $em->flush();

        return $this->redirectToRoute('list_books');
    }

    return $this->render('book/addBook.html.twig', [
        'form' => $form->createView(),
    ]);
}


   #[Route('/book/list', name: 'list_books')]
public function listBooks(BookRepository $bookRepository): Response
{
    $books = $bookRepository->findAll();

    // Compter les livres publiés et non publiés
    $publishedCount = $bookRepository->count(['published' => true]);
    $unpublishedCount = $bookRepository->count(['published' => false]);

    return $this->render('book/listBook.html.twig', [
        'books' => $books,
        'publishedCount' => $publishedCount,
        'unpublishedCount' => $unpublishedCount,
    ]);
}

    #[Route('/book/edit/{id}', name: 'edit_book')]
public function editBook(Request $request, EntityManagerInterface $em, Book $book): Response
{
    $form = $this->createForm(BookType::class, $book);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        return $this->redirectToRoute('list_books');
    }

    return $this->render('book/editBook.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route('/book/delete/{id}', name: 'delete_book')]
public function deleteBook(Book $book, EntityManagerInterface $em): Response
{
    $em->remove($book);
    $em->flush();

    return $this->redirectToRoute('list_books');
}

#[Route('/book/{id}', name: 'show_book')]
public function show(Book $book): Response
{
    return $this->render('book/showBook.html.twig', [
        'book' => $book,
    ]);
}

}
