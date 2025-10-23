<?php

namespace App\Controller;
use App\Entity\User;  
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

#[Route('/books/update-category', name: 'app_update_books_category')]
public function updateCategory(BookRepository $bookRepository): Response
{
    $count = $bookRepository->updateCategoryFromSciFiToRomance();

    return new Response("{$count} livres modifiés de Science-Fiction vers Romance.");
}



    #[Route('/books/old-authors', name: 'books_before_2023')]
public function booksBefore2023(BookRepository $bookRepository): Response
{
    $books = $bookRepository->findBooksBefore2023ByAuthorsWithMoreThan10Books();

    return $this->render('book/books_before_2023.html.twig', [
        'books' => $books,
    ]);
}

 #[Route('/book/list-by-authors', name: 'book_list_by_authors')]
public function listByAuthors(EntityManagerInterface $em): Response
{
    $books = $em->getRepository(Book::class)->booksListByAuthors();

    return $this->render('book/listBook.html.twig', [
        'books' => $books,
        'publishedCount' => 0,
        'unpublishedCount' => 0,
    ]);
}
#[Route('/book/search', name: 'book_search')]
public function search(Request $request, EntityManagerInterface $em): Response
{
    $ref = $request->query->get('ref');
    $books = [];

    if ($ref) {
        $books = $em->getRepository(Book::class)->searchBookByRef($ref);
    } else {
        $books = $em->getRepository(Book::class)->findAll();
    }

    // Calcule les compteurs
    $publishedCount = 0;
    $unpublishedCount = 0;
    foreach ($books as $book) {
        if ($book->isPublished()) {
            $publishedCount++;
        } else {
            $unpublishedCount++;
        }
    }

    return $this->render('book/listBook.html.twig', [
        'books' => $books,
        'ref' => $ref,
        'publishedCount' => $publishedCount,
        'unpublishedCount' => $unpublishedCount,
    ]);
}


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


#[Route('/books/count/romance', name: 'count_romance_books')]
public function countRomanceBooks(BookRepository $bookRepository)
{
    $count = $bookRepository->countBooksByCategory('Romance');
    return $this->render('book/count.html.twig', [
        'count' => $count,
    ]);
}

#[Route('/books/between', name: 'books_between')]
public function booksBetweenDates(BookRepository $bookRepository)
{
    $start = new \DateTime('2014-01-01');
    $end = new \DateTime('2018-12-31');
    $books = $bookRepository->findBooksBetweenDates($start, $end);

    return $this->render('book/list_between.html.twig', [
        'books' => $books,
    ]);
}



}
