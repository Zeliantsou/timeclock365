<?php

namespace App\DTO;

class BookFilterDTO
{
    private ?string $title = null;

    private ?string $authorName = null;

    private ?int $publishedYear = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    public function setAuthorName(?string $authorName): void
    {
        $this->authorName = $authorName;
    }

    public function getPublishedYear(): ?int
    {
        return $this->publishedYear;
    }

    public function setPublishedYear(?int $publishedYear): void
    {
        $this->publishedYear = $publishedYear;
    }
}
