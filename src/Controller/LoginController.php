<?php

namespace App\Controller;

use App\Entity\BookGenres;
use App\Entity\TopBooks;
use App\Repository\TopBooksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function HelloScreen(): Response{
        return $this->render('default.html.twig', [
            'stylesheets'=> ['reseted_password.css'],
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', [
            'controller_name' => 'LoginController',
            'last_username' => $lastUsername,
            'error'         => $error,
            'stylesheets'=> ['login.css'],
            'javascripts' => ['login.js'],
        ]);
    }

    #[Route('/my_profile', name: 'app_profile')]
    public function renderProfilePage(TopBooksRepository $topBooksRepository): Response
    {
        // Fetch the logged-in user
        $user = $this->getUser();

        $topBooks = $topBooksRepository->findOneBy(['user' => $user]);

        $booksData = $this->prepareBooksData($topBooks);

        return $this->render('profile_page.html.twig', [
            'stylesheets' => ['profile.css'],
            'booksData' => $booksData,
        ]);
    }

    private function prepareBooksData(?TopBooks $topBooks): array
    {
        $booksData = [];

        if ($topBooks !== null) {
            $books = [
                'Top One Book' => $topBooks->getTopOneBook(),
                'Top Two Book' => $topBooks->getTopTwoBook(),
                'Top Three Book' => $topBooks->getTopThreeBook()
            ];

            foreach ($books as $bookType => $book) {
                if ($book !== null) {
                    $genres = $book->getBookGenres()->map(function (BookGenres $genre) {
                        return $genre->getGenre()->getGenre();
                    })->toArray();

                    $booksData[$bookType] = [
                        'id' => $book->getId(),
                        'image' => $book->getImageUrlL(),
                        'title' => $book->getTitle(),
                        'pages' => $book->getPages(),
                        'author' => $book->getAuthor(),
                        'genres' => $genres,
                    ];
                }
            }
        }
        return $booksData;
    }
}
