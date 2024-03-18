<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Repository\AuthorRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    use BookFixturesTrait;

    public function __construct(
        private readonly AuthorRepository $authorRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $books = $this->getBooks(__DIR__.'/books.json');

        foreach ($books as $bookData) {
            $book = new Book(...$this->getBookPayload($bookData));
            $manager->persist($book);
        }

        $manager->flush();
    }

    private function getBookPayload(array $bookData): array
    {
        $description = array_key_exists('shortDescription', $bookData) ?
            $bookData['shortDescription'] : null;
        $publishedDate = array_key_exists('publishedDate', $bookData) ?
            $bookData['publishedDate']['$date'] : null;

        return [
            'title' => $bookData['title'],
            'description' => $description,
            'publishedYear' => $this->getPublishedYear($publishedDate),
            'authors' => new ArrayCollection($this->getAuthors($bookData['authors'])),
        ];
    }

    private function getPublishedYear(?string $publishedDate): ?int
    {
        if (!$publishedDate) {
            return null;
        }
        $publishedDate = new \DateTime($publishedDate);

        return (int) $publishedDate->format('Y');
    }

    private function getAuthors(array $authorNames): array
    {
        return $this->authorRepository->findBy(['fullName' => $authorNames]);
    }

    public static function getGroups(): array
    {
        return ['test'];
    }

    public function getDependencies(): array
    {
        return [AuthorFixtures::class];
    }
}
