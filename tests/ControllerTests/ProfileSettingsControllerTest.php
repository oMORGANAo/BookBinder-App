<?php

namespace App\Tests\ControllerTests;

use App\Repository\BookRepository;
use App\Repository\UserRepository;
use App\Tests\BaseTestCaseSetUp;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfileSettingsControllerTest extends BaseTestCaseSetUp
{
    /**
     * @throws Exception
     */
    public function testShow(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('spulska.tv@gmail.com');

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);

        // test e.g. the profile page
        $this->client->request('GET', '/profile_settings');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Update Your Data');
    }

    public function testShowRedirectWhenNotLoggedIn(): void
    {
        $this->client->request('GET', '/my_profile');

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame('http://localhost/login', $this->client->getResponse()->headers->get('Location'));
    }

    /**
     * @throws Exception
     */
    public function testEditUserSettings()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('spulska.tv@gmail.com');

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);

        // Define the path to the dummy image file
        $dummyImagePath = __DIR__.'/dummy_image.png';

        // Create the dummy image if it does not exist
        if (!file_exists($dummyImagePath)) {
            imagepng(imagecreatetruecolor(10, 10), $dummyImagePath);
        }

        // Create an UploadedFile instance for the dummy image
        $uploadedFile = new UploadedFile(
            $dummyImagePath,
            'dummy_image.png',
            'image/png',
            null,
            true
        );

        $crawler = $this->client->request('GET', '/profile_settings');

        $form = $crawler->selectButton('Update Data')->form();
        $form['profile_form[firstName]'] = 'Andre';
        $form['profile_form[surname]'] = 'Trafima';
        $form['profile_form[postalCode]'] = '3002';
        $form['profile_form[birthdate]'] = '2000-01-01';
        $form['profile_form[profilePicture]'] = $uploadedFile;

        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame('/my_profile', $this->client->getResponse()->headers->get('Location'));
    }

    /**
     * @throws Exception
     */
    public function testEditUserSettingsInvalidFirstName()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('spulska.tv@gmail.com');

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);

        // Define the path to the dummy image file
        $dummyImagePath = __DIR__.'/dummy_image.png';

        // Create the dummy image if it does not exist
        if (!file_exists($dummyImagePath)) {
            imagepng(imagecreatetruecolor(10, 10), $dummyImagePath);
        }

        // Create an UploadedFile instance for the dummy image
        $uploadedFile = new UploadedFile(
            $dummyImagePath,
            'dummy_image.png',
            'image/png',
            null,
            true
        );

        $crawler = $this->client->request('GET', '/profile_settings');

        $form = $crawler->selectButton('Update Data')->form();
        $form['profile_form[firstName]'] = 'andre';
        $form['profile_form[surname]'] = 'Trafima';
        $form['profile_form[postalCode]'] = '3002';
        $form['profile_form[birthdate]'] = '2000-01-01';
        $form['profile_form[profilePicture]'] = $uploadedFile;

        $this->client->submit($form);

        $this->assertFalse($this->client->getResponse()->isRedirect());
    }

    /**
     * @throws Exception
     */
    public function testEditUserSettingsInvalidSurname()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('spulska.tv@gmail.com');

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);

        // Define the path to the dummy image file
        $dummyImagePath = __DIR__.'/dummy_image.png';

        // Create the dummy image if it does not exist
        if (!file_exists($dummyImagePath)) {
            imagepng(imagecreatetruecolor(10, 10), $dummyImagePath);
        }

        // Create an UploadedFile instance for the dummy image
        $uploadedFile = new UploadedFile(
            $dummyImagePath,
            'dummy_image.png',
            'image/png',
            null,
            true
        );

        $crawler = $this->client->request('GET', '/profile_settings');

        $form = $crawler->selectButton('Update Data')->form();
        $form['profile_form[firstName]'] = 'Andrei';
        $form['profile_form[surname]'] = 'trafimau';
        $form['profile_form[postalCode]'] = '3002';
        $form['profile_form[birthdate]'] = '2000-01-01';
        $form['profile_form[profilePicture]'] = $uploadedFile;

        $this->client->submit($form);

        $this->assertFalse($this->client->getResponse()->isRedirect());
    }

    /**
     * @throws Exception
     */
    public function testEditUserSettingsInvalidPostalCode()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('spulska.tv@gmail.com');

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);

        // Define the path to the dummy image file
        $dummyImagePath = __DIR__.'/dummy_image.png';

        // Create the dummy image if it does not exist
        if (!file_exists($dummyImagePath)) {
            imagepng(imagecreatetruecolor(10, 10), $dummyImagePath);
        }

        // Create an UploadedFile instance for the dummy image
        $uploadedFile = new UploadedFile(
            $dummyImagePath,
            'dummy_image.png',
            'image/png',
            null,
            true
        );

        $crawler = $this->client->request('GET', '/profile_settings');

        $form = $crawler->selectButton('Update Data')->form();
        $form['profile_form[firstName]'] = 'Andrei';
        $form['profile_form[surname]'] = 'Trafimau';
        $form['profile_form[postalCode]'] = '300';
        $form['profile_form[birthdate]'] = '2000-01-01';
        $form['profile_form[profilePicture]'] = $uploadedFile;

        $this->client->submit($form);

        $this->assertFalse($this->client->getResponse()->isRedirect());
    }

    /**
     * @throws Exception
     */
    public function testEditUserSettingsWithTopBooks()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $bookRepository = static::getContainer()->get(BookRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('spulska.tv@gmail.com');


        // retrieve some test books
        $topOneBook = $bookRepository->find(3);
        $topTwoBook = $bookRepository->find(6);
        $topThreeBook = $bookRepository->find(10);

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);

        // Define the path to the dummy image file
        $dummyImagePath = __DIR__.'/dummy_image.png';

        // Create the dummy image if it does not exist
        if (!file_exists($dummyImagePath)) {
            imagepng(imagecreatetruecolor(10, 10), $dummyImagePath);
        }

        // Create an UploadedFile instance for the dummy image
        $uploadedFile = new UploadedFile(
            $dummyImagePath,
            'dummy_image.png',
            'image/png',
            null,
            true
        );

        $crawler = $this->client->request('GET', '/profile_settings');

        $form = $crawler->selectButton('Update Data')->form();
        $form['profile_form[firstName]'] = 'Andre';
        $form['profile_form[surname]'] = 'Trafima';
        $form['profile_form[postalCode]'] = '3002';
        $form['profile_form[birthdate]'] = '2000-01-01';
        $form['profile_form[profilePicture]'] = $uploadedFile;

        // Adding top books data to the form
        $form['topOneBook'] = $topOneBook->getId();
        $form['topTwoBook'] = $topTwoBook->getId();
        $form['topThreeBook'] = $topThreeBook->getId();

        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame('/my_profile', $this->client->getResponse()->headers->get('Location'));

        // Re-fetch the user from the database
        $updatedUser = $userRepository->findOneByEmail('spulska.tv@gmail.com');

        // Check if the top books have been updated correctly
        $this->assertEquals($topOneBook->getId(), $updatedUser->getTopBooks()->getTopOneBook()->getId());
        $this->assertEquals($topTwoBook->getId(), $updatedUser->getTopBooks()->getTopTwoBook()->getId());
        $this->assertEquals($topThreeBook->getId(), $updatedUser->getTopBooks()->getTopThreeBook()->getId());
    }

    public function testEditUserFirstTime()
    {

        $this->submitRegistrationForm();
        $container = $this->client->getContainer();

        $userRepository = $container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => $this->email]);
        $user->setIsVerified(true);


        // simulate $testUser being logged in
        $this->client->loginUser($user);

        // Define the path to the dummy image file
        $dummyImagePath = __DIR__.'/dummy_image.png';

        // Create the dummy image if it does not exist
        if (!file_exists($dummyImagePath)) {
            imagepng(imagecreatetruecolor(10, 10), $dummyImagePath);
        }

        // Create an UploadedFile instance for the dummy image
        $uploadedFile = new UploadedFile(
            $dummyImagePath,
            'dummy_image.png',
            'image/png',
            null,
            true
        );

        $crawler = $this->client->request('GET', '/profile_settings');

        $form = $crawler->selectButton('Update Data')->form();
        $form['profile_form[firstName]'] = 'Andre';
        $form['profile_form[surname]'] = 'Trafima';
        $form['profile_form[postalCode]'] = '3002';
        $form['profile_form[birthdate]'] = '2000-01-01';
        $form['profile_form[profilePicture]'] = $uploadedFile;


        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame('/my_profile', $this->client->getResponse()->headers->get('Location'));

        // Re-fetch the user from the database
        $updatedUser = $userRepository->findOneByEmail($user->getEmail());

        // Get the filename from the database
        $storedFilename = $updatedUser->getProfilePicture()->getFilename();

        // Extract the extension of the uploaded file
        $uploadedFileExtension = $uploadedFile->guessExtension();

        // Create a regular expression to match the filename pattern
        $filenamePattern = '/^dummy-image-\w+\\.' . preg_quote($uploadedFileExtension, '/') . '$/';

        // Assert that the stored filename matches the pattern
        $this->assertMatchesRegularExpression($filenamePattern, $storedFilename);
    }
}