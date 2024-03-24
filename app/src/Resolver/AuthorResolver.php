<?php

namespace App\Resolver;

use App\Entity\Author;
use App\Service\AuthorService;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

class AuthorResolver implements QueryInterface, MutationInterface, AliasedInterface
{
    public function __construct(
        private readonly AuthorService $authorService,
    ) {
    }

    public function getAuthors(): array
    {
        return $this->authorService->getAuthors();
    }

    public function getAuthor(string $id): Author
    {
        return $this->authorService->getAuthor($id);
    }

    public function createAuthor(array $data): Author
    {
        return $this->authorService->createAuthor($data);
    }

    public function updateAuthor(array $data): Author
    {
        return $this->authorService->updateAuthor($data);
    }

    public function deleteAuthor(string $id): string
    {
        return $this->authorService->deleteAuthor($id);
    }

    public static function getAliases(): array
    {
        return [
            'getAuthors' => 'authors',
            'getAuthor' => 'author',
            'createAuthor' => 'create_author',
            'updateAuthor' => 'update_author',
            'deleteAuthor' => 'delete_author',
        ];
    }
}
