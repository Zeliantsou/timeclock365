<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    protected Uuid $id;

    #[ORM\Column(type: 'string', unique: true)]
    protected string $fullName;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    protected int $bookAmount;

    public function __construct(
        string $fullName,
        int $bookAmount = 0,
        ?string $id = null
    ) {
        $this->fullName = $fullName;
        $this->bookAmount = $bookAmount;
        $this->id = (null !== $id) ? Uuid::fromString($id) : Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getBookAmount(): int
    {
        return $this->bookAmount;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function setBookAmount(int $bookAmount): void
    {
        $this->bookAmount = $bookAmount;
    }
}
