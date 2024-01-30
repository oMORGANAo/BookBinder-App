<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $genre = null;

    #[ORM\OneToMany(mappedBy: "genre", targetEntity: BookGenres::class, cascade: ["persist", "remove"])]
    private Collection $bookGenres;

    public function __construct()
    {
        $this->bookGenres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }
    public function getBookGenres(): Collection
    {
        return $this->bookGenres;
    }

    public function addBookGenre(BookGenres $bookGenre): self
    {
        if (!$this->bookGenres->contains($bookGenre)) {
            $this->bookGenres[] = $bookGenre;
            $bookGenre->setGenre($this);
        }

        return $this;
    }

    public function removeBookGenre(BookGenres $bookGenre): self
    {
        if ($this->bookGenres->contains($bookGenre)) {
            $this->bookGenres->removeElement($bookGenre);
            if ($bookGenre->getGenre() === $this) {
                $bookGenre->setGenre(null);
            }
        }

        return $this;
    }

}