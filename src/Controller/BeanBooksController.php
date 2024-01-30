<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Form\ReviewFormType;
use App\Repository\MyBooksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Book;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use App\Entity\MyBooks;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use App\Entity\TopBooks;
use App\Entity\ProfilePicture;
use App\Form\ProfileFormType;
use Exception;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Psr\Log\LoggerInterface;
use function Sodium\add;
use Doctrine\ORM\Query\Expr;
use App\Entity\BookGenres;
use Doctrine\Common\Collections\Criteria;
use App\Repository\BookGenresRepository;

use function Symfony\Component\DependencyInjection\Loader\Configurator\expr;

class BeanBooksController extends AbstractController
{
    // AbstractController = Symfony's base class
    private array $stylesheets;
    private EntityManagerInterface $entityManager;
    private UserInterface $user;

    public function __construct(
        EntityManagerInterface $entityManager,
        protected BookRepository $bookRepository,
        protected MyBooksRepository $myBooksRepository
    ) {
        $this->stylesheets[] = 'MyBooks.css';
        $this->entityManager = $entityManager;
    }

    public function updateMyBookEntries(
        Request $request) : void {

        $user = $this->getUser();
        $bookRepository = $this->entityManager->getRepository(Book::class);
        $myBooksRepository = $this->entityManager->getRepository(MyBooks::class);

        $greenBookId = $request->get('bookIdByGreenBean');
        $redBookId = $request->get('bookIdByRedBean');
        $plantBookId = $request->get('bookIdByPlant');
        $finishedBookId = $request->get('bookIdByFinished');
        if($greenBookId){
            $toUpdateMyBookEntry = $myBooksRepository->findOneBy(['book' => $greenBookId, 'user' => $user]);
            $toUpdateBookEntry = $bookRepository->findOneBy(['id' => $greenBookId]);
            if($toUpdateMyBookEntry->getBeaned() == 1){
                $toUpdateMyBookEntry->setBeaned(0);
                $toUpdateBookEntry->setBeans($toUpdateBookEntry->getBeans() - 1);
            }
            else{
                if($toUpdateMyBookEntry->getBadBeaned() == 1){
                    $toUpdateMyBookEntry->setBadBeaned(0);
                    $toUpdateBookEntry->setBadBeans($toUpdateBookEntry->getBadBeans() - 1);
                }
                $toUpdateMyBookEntry->setBeaned(1);
                $toUpdateBookEntry->setBeans($toUpdateBookEntry->getBeans() + 1);
            }
            $this->entityManager->persist($toUpdateMyBookEntry);
            $this->entityManager->persist($toUpdateBookEntry);
            $this->entityManager->flush();
        }
        if($redBookId){
            $toUpdateMyBookEntry = $myBooksRepository->findOneBy(['book' => $redBookId, 'user' => $user]);
            $toUpdateBookEntry = $bookRepository->findOneBy(['id' => $redBookId]);
            if($toUpdateMyBookEntry->getBadBeaned() == 1){
                $toUpdateMyBookEntry->setBadBeaned(0);
                $toUpdateBookEntry->setBadBeans($toUpdateBookEntry->getBadBeans() - 1);
            }
            else{
                if($toUpdateMyBookEntry->getBeaned() == 1){
                    $toUpdateMyBookEntry->setBeaned(0);
                    $toUpdateBookEntry->setBeans($toUpdateBookEntry->getBeans() - 1);
                }
                $toUpdateMyBookEntry->setBadBeaned(1);
                $toUpdateBookEntry->setBadBeans($toUpdateBookEntry->getBadBeans() + 1);
            }
            $this->entityManager->persist($toUpdateMyBookEntry);
            $this->entityManager->persist($toUpdateBookEntry);
            $this->entityManager->flush();
        }
        if($plantBookId){
            $toUpdateMyBookEntry = $myBooksRepository->findOneBy(['book' => $plantBookId, 'user' => $user]);
            $toUpdateBookEntry = $bookRepository->findOneBy(['id' => $plantBookId]);
            if($toUpdateMyBookEntry->getPlanted() == 1){
                $toUpdateMyBookEntry->setPlanted(0);
                $toUpdateBookEntry->setPlants($toUpdateBookEntry->getPlants() - 1);
            }
            else{
                $toUpdateMyBookEntry->setPlanted(1);
                $toUpdateBookEntry->setPlants($toUpdateBookEntry->getPlants() + 1);
            }
            $this->entityManager->persist($toUpdateMyBookEntry);
            $this->entityManager->persist($toUpdateBookEntry);
            $this->entityManager->flush();
        }
        if($finishedBookId){
            $toUpdateMyBookEntry = $myBooksRepository->findOneBy(['book' => $finishedBookId, 'user' => $user]);
            $toUpdateBookEntry = $bookRepository->findOneBy(['id' => $finishedBookId]);
            if($toUpdateMyBookEntry->getFinished() == 1){
                $toUpdateMyBookEntry->setFinished(0);
                $toUpdateBookEntry->setFinished($toUpdateBookEntry->getFinished() - 1);
            }
            else{
                $toUpdateMyBookEntry->setFinished(1);
                $toUpdateBookEntry->setFinished($toUpdateBookEntry->getFinished() + 1);
            }
            $this->entityManager->persist($toUpdateMyBookEntry);
            $this->entityManager->persist($toUpdateBookEntry);
            $this->entityManager->flush();
        }
    }

    #[Route('/myBooks', name: 'app_open_my_books')]
    public function RenderMyBooksPage(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $myBooks = $user->getMyBooks();

        $startTap = $request->get('startTap');
        if(!$startTap){
            $startTap = 'beaned';
        }
        $this->updateMyBookEntries($request);

        //book lists
        $beanedMyBooks = [];
        foreach ($myBooks as $myBook) {
            if ($myBook->getBeaned() === 1) {
                $beanedMyBooks[] = $myBook;
            }
        }
        $plantedMyBooks = [];
        foreach ($myBooks as $myBook) {
            if ($myBook->getPlanted() === 1) {
                $plantedMyBooks[] = $myBook;
            }
        }
        $finishedMyBooks = [];
        foreach ($myBooks as $myBook) {
            if ($myBook->getFinished() === 1) {
                $finishedMyBooks[] = $myBook;
            }
        }

        $this->stylesheets[] = 'myBooks.css';
        return $this->render('my_books.html.twig', [
            'stylesheets' => $this->stylesheets,
            'beanedMyBooks' => $beanedMyBooks,
            'plantedMyBooks' => $plantedMyBooks,
            'finishedMyBooks' => $finishedMyBooks,
            'startTap' => $startTap,
            'user1' => $user,
            'javascripts' => ['my_books.js']
        ]);
    }

    #[Route('/search', name: 'app_open_search')]
    public function renderSearchPage(Request $request): Response
    {
        $searched = $request->get('searched');
        $selected = $request->get('selected');
        $searchResultsBookEntry = [];
        $user = $this->getUser();
        $booksRepo = $this->entityManager->getRepository(Book::class);
        $myBooksRepository = $this->entityManager->getRepository(MyBooks::class);

        if ($selected === 'title') {
            ///////////// EXACT match
            // Search by title
            $exactMatchBooks = $booksRepo->findBy(['title' => $searched]);
            $searchResultsBookEntry = array_merge($searchResultsBookEntry, $exactMatchBooks);

            ///////////// WORD match
            // Search by title
            $wordMatchBooks = $booksRepo->createQueryBuilder('b')
                ->where('b.title LIKE :searched')
                ->setParameter('searched', '%' . $searched . '%')
                ->getQuery()
                ->getResult();

            foreach ($wordMatchBooks as $book) {
                if (!in_array($book, $searchResultsBookEntry)) {
                    $searchResultsBookEntry[] = $book;
                }
            }
            ///////////// CHARACTER match
            // Search by title
            $charMatchBooks = $booksRepo->createQueryBuilder('b')
                ->where('b.title LIKE :searched')
                ->andWhere('LENGTH(b.title) > 2')
                ->setParameter('searched', '%' . $searched . '%')
                ->getQuery()
                ->getResult();
            foreach ($charMatchBooks as $book) {
                if (!in_array($book, $searchResultsBookEntry)) {
                    $searchResultsBookEntry[] = $book;
                }
            }
        }
        elseif ($selected === 'author') {
            ///////////// EXACT match
            // Search by author
            $exactMatchAuthorBooks = $booksRepo->findBy(['author' => $searched]);
            $searchResultsBookEntry = array_merge($searchResultsBookEntry, $exactMatchAuthorBooks);

            ///////////// WORD match
            // Search by author
            $wordMatchAuthorBooks = $booksRepo->createQueryBuilder('b')
                ->where('b.author LIKE :searched')
                ->setParameter('searched', '%' . $searched . '%')
                ->getQuery()
                ->getResult();
            foreach ($wordMatchAuthorBooks as $book) {
                if (!in_array($book, $searchResultsBookEntry)) {
                    $searchResultsBookEntry[] = $book;
                }
            }
            ///////////// CHARACTER match
            // Search by author
            $charMatchAuthorBooks = $booksRepo->createQueryBuilder('b')
                ->where('b.author LIKE :searched')
                ->andWhere('LENGTH(b.author) > 2')
                ->setParameter('searched', '%' . $searched . '%')
                ->getQuery()
                ->getResult();
            foreach ($charMatchAuthorBooks as $book) {
                if (!in_array($book, $searchResultsBookEntry)) {
                    $searchResultsBookEntry[] = $book;
                }
            }
        }
        elseif ($selected === 'isbn') {
            ///////////// EXACT match
            // Search by ISBN
            $exactMatchISBNBooks = $booksRepo->findBy(['isbn' => $searched]);
            $searchResultsBookEntry = array_merge($searchResultsBookEntry, $exactMatchISBNBooks);

            ///////////// WORD match
            // Search by ISBN
            $wordMatchISBNBooks = $booksRepo->createQueryBuilder('b')
                ->where('b.isbn LIKE :searched')
                ->setParameter('searched', '%' . $searched . '%')
                ->getQuery()
                ->getResult();
            foreach ($wordMatchISBNBooks as $book) {
                if (!in_array($book, $searchResultsBookEntry)) {
                    $searchResultsBookEntry[] = $book;
                }
            }
            ///////////// CHARACTER match
            // Search by ISBN
            $charMatchISBNBooks = $booksRepo->createQueryBuilder('b')
                ->where('b.isbn LIKE :searched')
                ->andWhere('LENGTH(b.isbn) > 2')
                ->setParameter('searched', '%' . $searched . '%')
                ->getQuery()
                ->getResult();
            foreach ($charMatchISBNBooks as $book) {
                if (!in_array($book, $searchResultsBookEntry)) {
                    $searchResultsBookEntry[] = $book;
                }
            }
        }
        elseif ($selected === 'genre') {
            ///////////// EXACT match
            // Search by genre
            $exactMatchGenreBooks = $booksRepo->findBy(['genre' => $searched]);
            $searchResultsBookEntry = array_merge($searchResultsBookEntry, $exactMatchGenreBooks);

            ///////////// WORD match
            // Search by genre
            $wordMatchGenreBooks = $booksRepo->createQueryBuilder('b')
                ->where('b.genre LIKE :searched')
                ->setParameter('searched', '%' . $searched . '%')
                ->getQuery()
                ->getResult();
            foreach ($wordMatchGenreBooks as $book) {
                if (!in_array($book, $searchResultsBookEntry)) {
                    $searchResultsBookEntry[] = $book;
                }
            }
            ///////////// CHARACTER match
            // Search by genre
            $charMatchGenreBooks = $booksRepo->createQueryBuilder('b')
                ->where('b.genre LIKE :searched')
                ->andWhere('LENGTH(b.genre) > 2')
                ->setParameter('searched', '%' . $searched . '%')
                ->getQuery()
                ->getResult();
            foreach ($charMatchGenreBooks as $book) {
                if (!in_array($book, $searchResultsBookEntry)) {
                    $searchResultsBookEntry[] = $book;
                }
            }
        }

        $searchResultsBookEntry = array_slice($searchResultsBookEntry, 0, 10);
        $searchResultMyBookEntries = [];
        foreach ($searchResultsBookEntry as $searchResultsBookEntry){
            $searchResultMyBookEntry = $myBooksRepository->findOneBy(['book' => $searchResultsBookEntry, 'user' => $user]);
            if($searchResultMyBookEntry){
                $searchResultMyBookEntries[] = $searchResultMyBookEntry;
            }
            else{
                $searchResultMyBookEntry = new MyBooks($user,$searchResultsBookEntry);

                $this->entityManager->persist($searchResultMyBookEntry);
                $this->entityManager->flush();

                $searchResultMyBookEntries[] = $searchResultMyBookEntry;
            }
        }
        $this->updateMyBookEntries($request);

        $this->stylesheets[] = 'search.css';
        return $this->render('search.html.twig', [
            'stylesheets' => $this->stylesheets,
            'searchResultMyBookEntriesOfUser' => $searchResultMyBookEntries,
            'searched' => $searched,
            'selected' => $selected
         ]);
    }

    #[Route('/book', name: 'app_open_book')]
    public function renderBookPage(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // Repositories
        $bookRepository = $this->entityManager->getRepository(Book::class);
        $myBooksRepository = $this->entityManager->getRepository(MyBooks::class);
        $bookGenreRepository = $this->entityManager->getRepository(BookGenres::class);

        // Fetch the shown book entity
        $bookId = $request->query->getInt('bookId');
        $shownBook = $bookRepository->findOneBy(['id' => $bookId]);
        if (!$shownBook) {
            $bookId = 1;
            $shownBook = $bookRepository->findOneBy(['id' => $bookId]);
        }

        // Send review
        $review = $request->get('review');
        $myBook = $myBooksRepository->findOneBy(['book' => $shownBook, 'user' => $user]);
        if ($myBook) {
            // If the user already has the book in myBooks, toggle value and extract new value
            $myBook->setReview($review);
            $this->entityManager->persist($myBook);
            $this->entityManager->flush();
        } else {
            // If the user doesn't has the book in myBooks yet, create a new entity
            $myBook = new MyBooks($user,$shownBook);


            $this->entityManager->persist($myBook);
            $this->entityManager->flush();
        }

        // Fetch the genres of the shown book
        $bookGenres = $shownBook->getBookGenres();
        $genres = [];
        foreach ($bookGenres as $bookGenre) {
            $genres[] = $bookGenre->getGenre();
        }

        // Fetch MyBook entities that contain the shown book
        $myBookEntriesShownBook = $myBooksRepository->findBy(['book' => $bookId], []);
        $myBookEntriesWithReview = [];
        foreach ($myBookEntriesShownBook as $myBookEntry) {
            $myBookReview = $myBookEntry->getReview();
            if ($myBookReview != "" && isset($myBookReview)) {
                $myBookEntriesWithReview[] = $myBookEntry;
            }
        }

        // Fetch other books with the same genre
        $otherBookGenreEntries = $bookGenreRepository->findBy(['genre' => $genres[0]], [], 15);
        $otherBooks = [];
        foreach ($otherBookGenreEntries as $otherBookGenreEntry) {
            if ($otherBookGenreEntry->getBook()->getId() != $shownBook->getId()) {
                $otherBooks[] = $otherBookGenreEntry->getBook();
            }
        }

        //get the myBooks entry of the shown book
        $myBookEntryOfShownBook = $myBooksRepository->findOneBy(['book' => $shownBook, 'user' => $user]);

        //beanalytics
        $this->updateMyBookEntries($request);

        $this->stylesheets[] = 'book.css';
        return $this->render('book.html.twig', [
            'stylesheets' => $this->stylesheets,
            'shownBook' => $shownBook,
            'myBookEntryOfShownBook' => $myBookEntryOfShownBook,
            'myBookEntriesWithReview' => $myBookEntriesWithReview,
            'genres' => $genres,
            'otherBooks' => $otherBooks
        ]);
    }

    #[Route('/trending', name: 'app_trending_page')]
    public function RenderTrendingPage(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $topBooks  = $this->bookRepository->findBy([], ['beans' => 'DESC'], 10);
        $myBookEntriesOfTopBooks = [];
        foreach ($topBooks as $topBook){
            $myBookEntryOfTopBook = $this->myBooksRepository->findOneBy(['book' => $topBook, 'user' => $user]);
            if($myBookEntryOfTopBook){
                $myBookEntriesOfTopBooks[] = $myBookEntryOfTopBook;
            }
            else{
                $myBookEntryOfTopBook = new MyBooks($user,$topBook);

                $this->entityManager->persist($myBookEntryOfTopBook);
                $this->entityManager->flush();

                $myBookEntriesOfTopBooks[] = $myBookEntryOfTopBook;
            }
        }

        //update database when button is pressed
        $this->updateMyBookEntries($request);

        $this->stylesheets[] = 'Trending.css';
        return $this->render('trending.html.twig', [
            'stylesheets' => $this->stylesheets,
            'myBookEntriesOfTopBooks' => $myBookEntriesOfTopBooks,
            'user' => $user
        ]);
    }
}