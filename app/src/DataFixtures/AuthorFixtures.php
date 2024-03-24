<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture implements FixtureGroupInterface
{
    use BookFixturesTrait;

    public function __construct(
        private readonly AuthorRepository $authorRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $books = $this->getBooks(__DIR__.'/books.json');
        $authors = $this->getAuthors($books);

        foreach ($authors as $authorName => $bookAmount) {
            if (!$this->authorRepository->findOneBy(['fullName' => $authorName])) {
                $author = new Author();
                $author->setFullName($authorName);
                $author->setBookAmount($bookAmount);

                $manager->persist($author);
            }
        }

        $manager->flush();
    }

    private function getAuthors(array $books): array
    {
        $authors = [];
        $authorsData = [];

        foreach (array_column($books, 'authors') as $bookAuthors) {
            $authors = array_merge($authors, $bookAuthors);
        }

        foreach ($authors as $author) {
            if (!$author) {
                continue;
            }

            $author = ucwords(strtolower($author));

            if (!array_key_exists($author, $authorsData)) {
                $authorsData[$author] = 1;
            } else {
                ++$authorsData[$author];
            }
        }

        return $authorsData;
    }

    public static function getGroups(): array
    {
        return ['test'];
    }
}
