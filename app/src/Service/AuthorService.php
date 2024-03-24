<?php

namespace App\Service;

use App\Entity\Author;
use App\Exception\Author\AuthorInvalidSubmittedDataException;
use App\Exception\Author\AuthorNotFoundException;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class AuthorService
{
    public function __construct(
        private readonly AuthorRepository $repository,
        private readonly EntityManagerInterface $em,
        private readonly FormFactoryInterface $formFactory
    ) {
    }

    public function getAuthors(): array
    {
        return $this->repository->findAll();
    }

    public function getAuthor(string $id): Author
    {
        $author = $this->repository->find($id);

        if (!$author) {
            throw new AuthorNotFoundException($id);
        }

        return $author;
    }

    public function createAuthor(array $data): Author
    {
        $author = new Author();

        $form = $this->formFactory->create(AuthorType::class, $author);
        $form->submit($data);

        if ($form->isValid()) {
            $this->em->persist($author);
            $this->em->flush();

            return $author;
        }

        $error = $form->getErrors(true)[0];
        /* @phpstan-ignore-next-line */
        throw new AuthorInvalidSubmittedDataException($error->getMessage());
    }

    public function updateAuthor(array $data): Author
    {
        $author = $this->repository->find($data['id']);
        if (!$author) {
            throw new AuthorNotFoundException($data['id']);
        }

        unset($data['id']);

        $form = $this->formFactory->create(AuthorType::class, $author);
        $form->submit($data);

        if ($form->isValid()) {
            $this->em->persist($author);
            $this->em->flush();

            return $author;
        }

        $error = $form->getErrors(true)[0];
        /* @phpstan-ignore-next-line */
        throw new AuthorInvalidSubmittedDataException($error->getMessage());
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
