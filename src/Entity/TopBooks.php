<?php

namespace App\Entity;

use App\Repository\TopBooksRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TopBooksRepository::class)]
class TopBooks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'topBooks', cascade: ['persist'])]
    private ?User $user = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    private ?Book $top_one_book = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    private ?Book $top_two_book = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    private ?Book $top_three_book = null;

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

    public function getTopOneBook(): ?Book
    {
        return $this->top_one_book;
    }

    public function setTopOneBook(?Book $top_one_book): self
    {
        $this->top_one_book = $top_one_book;

        return $this;
    }

    public function getTopTwoBook(): ?Book
    {
        return $this->top_two_book;
    }

    public function setTopTwoBook(?Book $top_two_book): self
    {
        $this->top_two_book = $top_two_book;

        return $this;
    }

    public function getTopThreeBook(): ?Book
    {
        return $this->top_three_book;
    }

    public function setTopThreeBook(?Book $top_three_book): self
    {
        $this->top_three_book = $top_three_book;

        return $this;
    }
}
