<?php

namespace App\Exception\Book;

class BookInvalidFilterDataException extends \RuntimeException implements BookExceptionInterface
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
