<?php

namespace App\DataFixtures;

trait BookFixturesTrait
{
    protected function getBooks(string $filename): array
    {
        $booksJson = file_get_contents($filename);
        $books = is_string($booksJson) ? json_decode($booksJson, true) : [];

        return is_array($books) ? $books : [];
    }
}
