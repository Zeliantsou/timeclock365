<?php

namespace App\Resolver;

use App\Entity\Book;
use App\Service\BookService;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

class BookResolver implements QueryInterface, MutationInterface, AliasedInterface
{
    public function __construct(
        private readonly BookService $bookService,
    ) {
    }

    public function getBooks(): array
    {
        return $this->bookService->getBooks();
    }

    public function getBook(string $id): Book
    {
        return $this->bookService->getBook($id);
    }

    public function createBook(array $data): Book
    {
        $bookData = $this->bookService->decorateBefore($data);

        return $this->bookService->createBook($bookData);
    }

    public function updateBook(array $data): Book
    {
        $bookData = $this->bookService->decorateBefore($data);

        return $this->bookService->updateBook($bookData);
    }

    public function deleteBook(string $id): string
    {
        return $this->bookService->deleteBook($id);
    }

    public static function getAliases(): array
    {
        return [
            'getBooks' => 'books',
            'getBook' => 'book',
            'createBook' => 'create_book',
            'updateBook' => 'update_book',
            'deleteBook' => 'delete_book',
        ];
    }
}
