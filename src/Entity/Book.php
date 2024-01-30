<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $isbn = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image_url_s = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image_url_m = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image_url_l = null;

    #[ORM\Column(nullable: true)]
    private ?int $beans = null;

    #[ORM\Column(nullable: true)]
    private ?int $bad_beans = null;

    #[ORM\Column(nullable: true)]
    private ?int $plants = null;

    #[ORM\Column]
    private ?int $finished = null;

    #[ORM\Column]
    private ?int $pages = null;

    #[ORM\OneToMany(mappedBy: "book", targetEntity: BookGenres::class, cascade: ["persist", "remove"])]
    private Collection $genres;

    #[ORM\OneToMany(mappedBy: "book", targetEntity: MyBooks::class)]
    private Collection $myBooks;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
        $this->myBooks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsbn(): ?float
    {
        return $this->isbn;
    }

    public function setIsbn(?float $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getImageUrlS(): ?string
    {
        return $this->image_url_s;
    }

    public function setImageUrlS(?string $image_url_s): self
    {
        $this->image_url_s = $image_url_s;

        return $this;
    }

    public function getImageUrlM(): ?string
    {
        return $this->image_url_m;
    }

    public function setImageUrlM(?string $image_url_m): self
    {
        $this->image_url_m = $image_url_m;

        return $this;
    }

    public function getImageUrlL(): ?string
    {
        return $this->image_url_l;
    }

    public function setImageUrlL(?string $image_url_l): self
    {
        $this->image_url_l = $image_url_l;

        return $this;
    }

    public function getBeans(): ?int
    {
        return $this->beans;
    }

    public function setBeans(?int $beans): self
    {
        $this->beans = $beans;

        return $this;
    }

    public function getBadBeans(): ?int
    {
        return $this->bad_beans;
    }

    public function setBadBeans(?int $bad_beans): self
    {
        $this->bad_beans = $bad_beans;

        return $this;
    }

    public function getPlants(): ?int
    {
        return $this->plants;
    }

    public function setPlants(?int $plants): self
    {
        $this->plants = $plants;

        return $this;
    }

    public function getFinished(): ?int
    {
        return $this->finished;
    }

    public function setFinished(int $finished): self
    {
        $this->finished = $finished;

        return $this;
    }

    public function getPages(): ?int
    {
        return $this->pages;
    }

    public function setPages(int $pages): self
    {
        $this->pages = $pages;

        return $this;
    }

    /**
     * @return Collection<int, BookGenres>
     */
    public function getBookGenres(): Collection
    {
        return $this->genres;
    }

    public function addBookGenre(BookGenres $bookGenre): self
    {
        if (!$this->genres->contains($bookGenre)) {
            $this->genres[] = $bookGenre;
            $bookGenre->setBook($this);
        }

        return $this;
    }

    public function removeBookGenre(BookGenres $bookGenre): self
    {
        if ($this->genres->removeElement($bookGenre)) {
            if ($bookGenre->getBook() === $this) {
                $bookGenre->removeBook();
            }
        }

        return $this;
    }

    public function getMyBooks(): Collection
    {
        return $this->myBooks;
    }
}