<?php

namespace App\Exception\Author;

class AuthorNotFoundException extends \RuntimeException implements AuthorExceptionInterface
{
    public function __construct(string $id, int $code = 0, ?\Throwable $previous = null)
    {
        $message = sprintf('Author with id = %s not found in system', $id);

        parent::__construct($message, $code, $previous);
    }
}
