<?php

namespace App\Service;

use App\Entity\Book;
use App\Exception\Book\BookInvalidSubmittedDataException;
use App\Exception\Book\BookNotFoundException;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class BookService
{
    public function __construct(
        private readonly BookRepository $repository,
        private readonly EntityManagerInterface $em,
        private readonly FormFactoryInterface $formFactory
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
        $book = new Book();

        $form = $this->formFactory->create(
            BookType::class, $book
        );
        $form->submit($data);

        if ($form->isValid()) {
            $this->em->persist($book);
            $this->em->flush();

            return $book;
        }

        $error = $form->getErrors(true)[0];
        /* @phpstan-ignore-next-line */
        throw new BookInvalidSubmittedDataException($error->getMessage());
    }

    public function updateBook(array $data): Book
    {
        $book = $this->repository->find($data['id']);

        if (!$book) {
            throw new BookNotFoundException($data['id']);
        }

        unset($data['id']);

        $form = $this->formFactory->create(
            BookType::class, $book
        );
        $form->submit($data);

        if ($form->isValid()) {
            $this->em->persist($book);
            $this->em->flush();

            return $book;
        }

        $error = $form->getErrors(true)[0];
        /* @phpstan-ignore-next-line */
        throw new BookInvalidSubmittedDataException($error->getMessage());
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
