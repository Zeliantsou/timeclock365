<?php

namespace App\Exception\Book;

class BookInvalidSubmittedDataException extends \RuntimeException implements BookExceptionInterface
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
