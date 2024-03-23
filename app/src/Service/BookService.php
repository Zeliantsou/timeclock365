<?php

namespace App\Service;

use App\Entity\Book;
use App\Exception\Book\BookNotFoundException;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;

class BookService
{
    public function __construct(
        private readonly BookRepository $repository,
        private readonly EntityManagerInterface $em
    ) {
    }

    public function getBook(string $id): ?Book
    {
        return $this->repository->find($id);
    }

    public function getBooks(): array
    {
        return $this->repository->findAll();
    }

    public function createBook(array $data): Book
    {
        $author = new Book(
            $data['title'],
            $data['authors'],
            $data['description'],
            $data['publishedYear']
        );

        $this->em->persist($author);
        $this->em->flush();

        return $author;
    }

    public function updateBook(array $data): Book
    {
        $book = $this->repository->find($data['id']);

        if (!$book) {
            throw new BookNotFoundException($data['id']);
        }

        $book->setTitle($data['title']);
        $book->setAuthors($data['authors']);
        $book->setDescription($data['description']);
        $book->setPublishedYear($data['publishedYear']);

        $this->em->persist($book);
        $this->em->flush();

        return $book;
    }

    public function deleteBook(string $id): string
    {
        $book = $this->repository->find($id);

        if (!$book) {
            throw new BookNotFoundException($id);
        }

        $this->em->remove($book);
        $this->em->flush();

        return sprintf('Book with id = %s successfully removed', $id);
    }
}
