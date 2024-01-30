<?php

namespace App\Entity;

use App\Repository\BookGenresRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookGenresRepository::class)]
class BookGenres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: "genres")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    #[ORM\ManyToOne(targetEntity: Genre::class, inversedBy: 'bookGenres')]
    #[ORM\JoinColumn(name: 'genre_id', referencedColumnName: 'id')]
    private ?Genre $genre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Book
     */
    public function getBook(): Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function removeBook(): self
    {
        $this->book = null;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }
}