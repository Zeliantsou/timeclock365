<?php

namespace App\Exception\Author;

class AuthorInvalidSubmittedDataException extends \RuntimeException implements AuthorExceptionInterface
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
