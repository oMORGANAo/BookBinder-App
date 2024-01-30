<?php

namespace App\Entity;

use App\Repository\MyBooksRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MyBooksRepository::class)]
class MyBooks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "myBooks")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: "myBooks")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $planted = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $beaned = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $badBeaned = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $finished = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $review = null;

    public function __construct(User $user = null, Book $book = null)
    {
        $this->user = $user;
        $this->book = $book;
        $this->beaned = 0;
        $this->planted = 0;
        $this->badBeaned = 0;
        $this->finished = 0;
        $this->review = null;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getPlanted(): ?int
    {
        return $this->planted;
    }

    public function setPlanted(int $planted): self
    {
        $this->planted = $planted;

        return $this;
    }

    public function getBeaned(): ?int
    {
        return $this->beaned;
    }

    public function setBeaned(int $beaned): self
    {
        $this->beaned = $beaned;

        return $this;
    }

    public function getBadBeaned(): ?int
    {
        return $this->badBeaned;
    }


    public function setBadBeaned(?int $badBeaned): void
    {
        $this->badBeaned = $badBeaned;
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

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function setReview(?string $review): self
    {
        $this->review = $review;

        return $this;
    }
}
