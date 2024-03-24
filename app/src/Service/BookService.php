<?php

namespace App\Service;

use App\DTO\BookFilterDTO;
use App\Entity\Book;
use App\Exception\Book\BookInvalidFilterDataException;
use App\Exception\Book\BookInvalidSubmittedDataException;
use App\Exception\Book\BookNotFoundException;
use App\Form\BookType;
use App\Form\DTO\BookFilterDTOType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Uid\UuidV4;

class BookService
{
    public function __construct(
        private readonly BookRepository $repository,
        private readonly EntityManagerInterface $em,
        private readonly FormFactoryInterface $formFactory
    ) {
    }

    public function getBooks(?array $filterData = null): array
    {
        if (!$filterData) {
            return $this->repository->findAll();
        }

        $filterDTO = new BookFilterDTO();
        $form = $this->formFactory->create(BookFilterDTOType::class, $filterDTO);
        $form->submit($filterData);

        if ($form->isValid()) {
            /* @phpstan-ignore-next-line */
            return $this->repository->findByParams($form->getData());
        }

        $error = $form->getErrors(true)[0];
        /* @phpstan-ignore-next-line */
        throw new BookInvalidFilterDataException($error->getMessage());
    }

    public function getBook(string $id): Book
    {
        if (!$this->isBookExist($id)) {
            throw new BookNotFoundException($id);
        }

        return $this->repository->find($id); /* @phpstan-ignore-line */
    }

    public function createBook(array $data): Book
    {
        $book = new Book();

        $form = $this->formFactory->create(BookType::class, $book);
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
        if (!$this->isBookExist($data['id'])) {
            throw new BookNotFoundException($data['id']);
        }

        $book = $this->repository->find($data['id']);

        $form = $this->formFactory->create(BookType::class, $book);
        unset($data['id']);
        $form->submit($data);

        if ($form->isValid()) {
            $this->em->persist($book); /* @phpstan-ignore-line */
            $this->em->flush();

            return $book; /* @phpstan-ignore-line */
        }

        $error = $form->getErrors(true)[0];
        /* @phpstan-ignore-next-line */
        throw new BookInvalidSubmittedDataException($error->getMessage());
    }

    public function deleteBook(string $id): string
    {
        if (!$this->isBookExist($id)) {
            throw new BookNotFoundException($id);
        }

        $this->em->remove($this->repository->find($id)); /* @phpstan-ignore-line */
        $this->em->flush();

        return sprintf('Book with id = %s successfully removed', $id);
    }

    public function decorateBefore(array $data): array
    {
        if (array_key_exists('authors', $data)) {
            $data['authors'] = explode(', ', $data['authors']);
        }

        return $data;
    }

    private function isBookExist(string $id): bool
    {
        return UuidV4::isValid($id) && $this->repository->find($id);
    }
}
