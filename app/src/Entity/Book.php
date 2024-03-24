<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    protected Uuid $id;

    #[ORM\Column(type: 'string')]
    protected ?string $title = null;

    #[ORM\ManyToMany(targetEntity: Author::class)]
    protected Collection $authors;

    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $description = null;

    #[ORM\Column(type: 'smallint', nullable: true)]
    protected ?int $publishedYear = null;

    public function __construct(?string $id = null)
    {
        $this->id = (null !== $id) ? Uuid::fromString($id) : Uuid::v4();
        $this->authors = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPublishedYear(): ?int
    {
        return $this->publishedYear;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function setAuthors(Collection $authors): void
    {
        $this->authors = $authors;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function setPublishedYear(?int $publishedYear): void
    {
        $this->publishedYear = $publishedYear;
    }

    public function addAuthor(Author $author): void
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }
    }

    public function removeAuthor(Author $author): void
    {
        if ($this->authors->contains($author)) {
            $this->authors->removeElement($author);
        }
    }
}
