<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function countBooksByCategory(string $category): int
{
    return $this->createQueryBuilder('b')
        ->select('COUNT(b.id)')
        ->where('b.category = :cat')
        ->setParameter('cat', $category)
        ->getQuery()
        ->getSingleScalarResult();
}


public function findBooksBetweenDates(\DateTime $start, \DateTime $end)
{
    return $this->createQueryBuilder('b')
        ->where('b.publicationDate BETWEEN :start AND :end')
        ->setParameter('start', $start)
        ->setParameter('end', $end)
        ->getQuery()
        ->getResult();
}
public function searchBookByRef(string $ref)
{
    return $this->createQueryBuilder('b')
        ->where('b.ref = :ref')
        ->setParameter('ref', $ref)
        ->getQuery()
        ->getResult();
}
public function booksListByAuthors(): array
{
    return $this->createQueryBuilder('b')
        ->join('b.user', 'u')          // jointure avec l’auteur (User)
        ->addSelect('u')               // pour récupérer aussi l’auteur
        ->orderBy('u.username', 'ASC') // tri par nom d’auteur (ordre alphabétique)
        ->addOrderBy('b.title', 'ASC') // (optionnel) tri secondaire par titre
        ->getQuery()
        ->getResult();
}

public function findBooksBefore2023ByAuthorsWithMoreThan10Books(): array
{
    $qb = $this->createQueryBuilder('b')
        ->join('b.user', 'a')
        ->where('b.published = 1')
        ->andWhere('b.publicationDate < :limitDate')
        ->andWhere(
            '(SELECT COUNT(b2.id)
              FROM App\Entity\Book b2
              WHERE b2.user = a.id
            ) > 10'
        )
        ->setParameter('limitDate', new \DateTime('2023-01-01'))
        ->orderBy('a.username', 'ASC');

    return $qb->getQuery()->getResult();
}

public function updateCategoryFromSciFiToRomance(): int
{
    $qb = $this->createQueryBuilder('b')
        ->update()
        ->set('b.category', ':newCategory')
        ->where('b.category = :oldCategory')
        ->setParameter('newCategory', 'Romance')
        ->setParameter('oldCategory', 'Science-Fiction');

    return $qb->getQuery()->execute(); 
}




}
