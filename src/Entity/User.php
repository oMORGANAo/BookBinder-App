<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $surname = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $postalCode = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    /**
     * @var string|null
     */
    private ?string $plainPassword = null;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: ProfilePicture::class, cascade: ['persist', 'remove'])]
    private ?ProfilePicture $profilePicture = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: MyBooks::class)]
    private Collection $myBooks;


    #[ORM\OneToOne(mappedBy: 'user', targetEntity: TopBooks::class, cascade: ['persist','remove'])]
    private ?TopBooks $topBooks;

    public function __construct() {
        $this->myBooks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     * @return self
     */
    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getProfilePicture(): ?ProfilePicture
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?ProfilePicture $profilePicture): self
    {
        if ($profilePicture === null && $this->profilePicture !== null) {
            $this->profilePicture->setUser(null);
        }

        if ($profilePicture !== null && $profilePicture->getUser() !== $this) {
            $profilePicture->setUser($this);
        }

        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function getMyBooks(): Collection
    {
        return $this->myBooks;
    }

    public function getBeanedBooks(): Collection
    {
        return $this->myBooks->filter(function(MyBooks $myBooks) {
            return $myBooks->getBeaned() === 1;
        });
    }

    public function getTopBooks(): ?TopBooks
    {
        return $this->topBooks;
    }

    public function setTopBooks(?TopBooks $topBooks): self
    {
        $this->topBooks = $topBooks;

        $newUser = null === $topBooks ? null : $this;
        if ($topBooks->getUser() !== $newUser) {
            $topBooks->setUser($newUser);
        }

        return $this;
    }

}
