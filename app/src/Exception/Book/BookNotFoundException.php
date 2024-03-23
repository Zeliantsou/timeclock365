<?php

namespace App\Exception\Book;

class BookNotFoundException extends \RuntimeException implements BookExceptionInterface
{
    public function __construct(string $id, int $code = 0, ?\Throwable $previous = null)
    {
        $message = sprintf('Book with id = %s not found in system', $id);

        parent::__construct($message, $code, $previous);
    }
}
