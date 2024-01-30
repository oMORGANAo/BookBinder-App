<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\TopBooks;
use App\Entity\User;
use App\Entity\ProfilePicture;
use App\Form\ProfileFormType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Psr\Log\LoggerInterface;

class ProfileSettingsController extends AbstractController
{
    private array $stylesheets;
    private SluggerInterface $slugger;
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    public function __construct(SluggerInterface $slugger, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    #[Route('/profile_settings', name: 'app_profile_settings', methods: ['GET', 'POST'])]
    public function RenderProfileSettingsPage(Request $request): Response
    {
        $this->stylesheets[] = 'profile_settings.css';

        /** @var User $user */
        $user = $this->getUser();
        $myBooks = $user->getMyBooks();

        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $profilePictureFile */
            $profilePictureFile = $form['profilePicture']->getData();

            $bookRepo = $this->entityManager->getRepository(Book::class);

            $topBooks = $user->getTopBooks();
            if (!$topBooks) {
                $topBooks = new TopBooks();
                $topBooks->setUser($user);
            }

            $topOneBookId = $request->request->get('topOneBook');
            if ($topOneBookId) {
                $topOneBook = $bookRepo->find($topOneBookId);
                $topBooks->setTopOneBook($topOneBook);
            }

            $topTwoBookId = $request->request->get('topTwoBook');
            if ($topTwoBookId) {
                $topTwoBook = $bookRepo->find($topTwoBookId);
                $topBooks->setTopTwoBook($topTwoBook);
            }

            $topThreeBookId = $request->request->get('topThreeBook');
            if ($topThreeBookId) {
                $topThreeBook = $bookRepo->find($topThreeBookId);
                $topBooks->setTopThreeBook($topThreeBook);
            }

            $this->entityManager->persist($topBooks);


            try {
                $this->entityManager->flush();
            } catch (Exception $e) {
                $this->logger->error('Failed to update database', ['exception' => $e]);
                $this->addFlash('error', 'Failed to update database. Please try again.');

                return $this->redirectToRoute('app_profile_settings');
            }

            if ($profilePictureFile) {
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profilePictureFile->guessExtension();

                $profilePicture = $user->getProfilePicture();
                // If the user already has a profile picture, remove the old file
                if ($profilePicture) {
                    $oldFilename = $profilePicture->getFilename();
                    if ($oldFilename && file_exists($this->getParameter('profile_picture_directory').'/'.$oldFilename)) {
                        unlink($this->getParameter('profile_picture_directory').'/'.$oldFilename);
                    }
                } else {
                    $profilePicture = new ProfilePicture();
                    $profilePicture->setUser($user);
                    $user->setProfilePicture($profilePicture);
                }

                try {
                    $profilePictureFile->move(
                        $this->getParameter('profile_picture_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->logger->error('Failed to upload file', ['exception' => $e]);
                    $this->addFlash('error', 'Failed to upload profile picture. Please try again.');

                    return $this->redirectToRoute('app_profile_settings');
                }

                $profilePicture->setFilename($newFilename);

                try {
                    $this->entityManager->persist($profilePicture);
                    $this->entityManager->persist($user);
                    $this->entityManager->flush();
                } catch (Exception $e) {
                    $this->logger->error('Failed to save profile picture', ['exception' => $e]);
                    $this->addFlash('error', 'Failed to save profile picture. Please try again.');

                    return $this->redirectToRoute('app_profile_settings');
                }
            }

            try {
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $this->addFlash('success', 'Profile updated successfully');
                return $this->redirectToRoute('app_profile');
            } catch (Exception $e) {
                $this->logger->error('Failed to update profile', ['exception' => $e]);
                $this->addFlash('error', 'Failed to update profile. Please try again.');

                return $this->redirectToRoute('app_profile_settings');
            }
        }

        $beanedBooks = [];
        foreach($myBooks as $myBook) {
            if($myBook->getBeaned() === 1) {
                $beanedBooks[] = $myBook->getBook();
            }
        }

        return $this->render('profile_settings.html.twig', [
            'updateUserForm' => $form->createView(),
            'stylesheets' => $this->stylesheets,
            'beanedBooks' => $beanedBooks,
            'javascripts' => ['profile_settings.js'],
        ]);
    }
}
