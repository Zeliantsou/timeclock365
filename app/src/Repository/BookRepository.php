<?php

namespace App\Repository;

use App\DTO\BookFilterDTO;
use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findByParams(BookFilterDTO $filterDTO): array
    {
        $queryBuilder = $this->createQueryBuilder('book')
            ->innerJoin('book.authors', 'author');

        if ($filterDTO->getTitle()) {
            $queryBuilder
                ->orWhere($queryBuilder->expr()->like('book.title', ':partOfTitle'))
                ->setParameter('partOfTitle', '%'.$filterDTO->getTitle().'%');
        }

        if ($filterDTO->getAuthorName()) {
            $queryBuilder
                ->orWhere($queryBuilder->expr()->like('author.fullName', ':partOfName'))
                ->setParameter('partOfName', '%'.$filterDTO->getAuthorName().'%');
        }

        if ($filterDTO->getPublishedYear()) {
            $queryBuilder
                ->orWhere('book.publishedYear = :publishedYear')
                ->setParameter('publishedYear', $filterDTO->getPublishedYear());
        }

        $books = $queryBuilder
            ->getQuery()
            ->getResult()
        ;

        assert(is_array($books));

        return $books;
    }
}
