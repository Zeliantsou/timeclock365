<?php

namespace App\Service;

use App\Entity\Author;
use App\Exception\Author\AuthorInvalidSubmittedDataException;
use App\Exception\Author\AuthorNotFoundException;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Uid\UuidV4;

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
        if (!$this->isAuthorExist($id)) {
            throw new AuthorNotFoundException($id);
        }

        return $this->repository->find($id); /* @phpstan-ignore-line */
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
        if (!$this->isAuthorExist($data['id'])) {
            throw new AuthorNotFoundException($data['id']);
        }

        $author = $this->repository->find($data['id']);

        $form = $this->formFactory->create(AuthorType::class, $author);
        unset($data['id']);
        $form->submit($data);

        if ($form->isValid()) {
            $this->em->persist($author); /* @phpstan-ignore-line */
            $this->em->flush();

            return $author; /* @phpstan-ignore-line */
        }

        $error = $form->getErrors(true)[0];
        /* @phpstan-ignore-next-line */
        throw new AuthorInvalidSubmittedDataException($error->getMessage());
    }

    public function deleteAuthor(string $id): string
    {
        if (!$this->isAuthorExist($id)) {
            throw new AuthorNotFoundException($id);
        }

        $this->em->remove($this->repository->find($id)); /* @phpstan-ignore-line */
        $this->em->flush();

        return sprintf('Author with id = %s successfully removed', $id);
    }

    private function isAuthorExist(string $id): bool
    {
        return UuidV4::isValid($id) && $this->repository->find($id);
    }
}
