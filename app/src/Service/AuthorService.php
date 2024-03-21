<?php

namespace App\Service;

use App\Entity\Author;
use App\Exception\Author\AuthorNotFoundException;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;

class AuthorService
{
    public function __construct(
        private readonly AuthorRepository $repository,
        private readonly EntityManagerInterface $em
    ) {
    }

    // add find by
    public function getAuthor(string $id): ?Author
    {
        return $this->repository->find($id);
    }

    // add filters and ordering
    public function getAuthors(): array
    {
        return $this->repository->findAll();
    }

    // add validation
    public function createAuthor(array $data): Author
    {
        $author = new Author($data['fullName'], $data['bookAmount']);

        $this->em->persist($author);
        $this->em->flush();

        return $author;
    }

    // add validation
    // add listener for adding book amount when book created
    public function updateAuthor(array $data): Author
    {
        $author = $this->repository->find($data['id']);

        if (!$author) {
            throw new AuthorNotFoundException($data['id']);
        }

        // check if empty
        $author->setFullName($data['fullName']);
        $author->setBookAmount($data['bookAmount']);

        $this->em->persist($author);
        $this->em->flush();

        return $author;
    }

    public function deleteAuthor(string $id): string
    {
        $author = $this->repository->find($id);

        if (!$author) {
            throw new AuthorNotFoundException($id);
        }

        $this->em->remove($author);
        $this->em->flush();

        return sprintf('Author with id = %s successfully removed', $id);
    }
}
