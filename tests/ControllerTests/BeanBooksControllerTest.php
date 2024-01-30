<?php

namespace App\Tests\ControllerTests;

use App\Entity\Book;
use App\Entity\BookGenres;
use App\Entity\Genre;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BeanBooksControllerTest extends WebTestCase
{
    ////SEARCH/////
    public function testTitleSearchPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Submit the form with some bad credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);


        // Make a request to the search page with the desired parameters
        $client->request('GET', '/search', [
            'searched' => 'Harry Potter',
            'selected' => 'title',
            'bookIdByGreenBean' => 1,
        ]);


        // Assert that the response is successful
        $this->assertResponseIsSuccessful();


        // Assert any other necessary assertions based on the expected behavior of the controller method
        // For example, you can assert that the search results are displayed correctly in the rendered template.
    }
    public function testAuthorSearchPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Submit the form with some bad credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);


        // Make a request to the search page with the desired parameters
        $client->request('GET', '/search', [
            'searched' => 'Harry Potter',
            'selected' => 'author',
            'bookIdByGreenBean' => 1,
        ]);


        // Assert that the response is successful
        $this->assertResponseIsSuccessful();


        // Assert any other necessary assertions based on the expected behavior of the controller method
        // For example, you can assert that the search results are displayed correctly in the rendered template.
    }
    public function testISBNSearchPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Submit the form with some bad credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);


        // Make a request to the search page with the desired parameters
        $client->request('GET', '/search', [
            'searched' => 'Harry Potter',
            'selected' => 'isbn',
            'bookIdByGreenBean' => 1,
        ]);


        // Assert that the response is successful
        $this->assertResponseIsSuccessful();


        // Assert any other necessary assertions based on the expected behavior of the controller method
        // For example, you can assert that the search results are displayed correctly in the rendered template.
    }
    public function testExactMatch()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Submit the form with some bad credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);


        // Simulate the selected option
        $selected = 'title';
        // Simulate the searched term
        $searched = 'Classical Mythology';

        // Make a request to the search page with the desired parameters
        $client->request('GET', '/search', [
            'searched' => $searched,
            'selected' => $selected,

        ]);
        $crawler = $client->request('GET', '/search', [
            'searched' => $searched,
            'selected' => $selected,
        ]);

        ///////////// EXACT match

        // Use the filter method to select elements with class "bookTitle"
        $bookTitleElements = $crawler->filter('.bookTitle');

        // Assert that the searched term has an exact match with at least one of the "bookTitle" elements
        $found = false;
        foreach ($bookTitleElements as $bookTitleElement) {
            if ($bookTitleElement->textContent === $searched) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);

    }
    public function testWordMatch()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Submit the form with some bad credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);


        // Simulate the selected option
        $selected = 'title';
        // Simulate the searched term
        $searched = 'Classical';

        // Make a request to the search page with the desired parameters
        $client->request('GET', '/search', [
            'searched' => $searched,
            'selected' => $selected,

        ]);
        $crawler = $client->request('GET', '/search', [
            'searched' => $searched,
            'selected' => $selected,
        ]);

        ///////////// WORD match

        // Use the filter method to select elements with class "bookTitle"
        $bookTitleElements = $crawler->filter('.bookTitle');

        // Assert that the searched term is found in at least one of the "bookTitle" elements
        $found = false;
        foreach ($bookTitleElements as $bookTitleElement) {
            if (stripos($bookTitleElement->textContent, $searched) !== false) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);
    }
    public function testCharacterMatch()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Submit the form with some bad credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);


        // Simulate the selected option
        $selected = 'title';
        // Simulate the searched term
        $searched = 'Clas';

        // Make a request to the search page with the desired parameters
        $client->request('GET', '/search', [
            'searched' => $searched,
            'selected' => $selected,

        ]);
        $crawler = $client->request('GET', '/search', [
            'searched' => $searched,
            'selected' => $selected,
        ]);

        ///////////// WORD match

        // Use the filter method to select elements with class "bookTitle"
        $bookTitleElements = $crawler->filter('.bookTitle');

        // Assert that at least one of the "bookTitle" elements has at least three corresponding characters with the searched term
        $found = false;
        foreach ($bookTitleElements as $bookTitleElement) {
            $title = $bookTitleElement->textContent;
            $correspondingCharacters = 0;

            // Compare each character in the title with the searched term
            for ($i = 0; $i < strlen($searched); $i++) {
                if (stripos($title, $searched[$i]) !== false) {
                    $correspondingCharacters++;
                }
            }

            if ($correspondingCharacters >= 3) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);
}
    public function testAuthorExactMatch()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Submit the form with some bad credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);


        // Simulate the selected option
        $selected = 'author';
        // Simulate the searched term
        $searched = 'Roy';

        // Make a request to the search page with the desired parameters
        $client->request('GET', '/search', [
            'searched' => $searched,
            'selected' => $selected,

        ]);
        $crawler = $client->request('GET', '/search', [
            'searched' => $searched,
            'selected' => $selected,
        ]);

        ///////////// EXACT match

        // Use the filter method to select elements with class "bookTitle"
        $bookTitleElements = $crawler->filter('.bookAuthor');

        // Assert that the searched term has an exact match with at least one of the "bookTitle" elements
        $found = false;
        foreach ($bookTitleElements as $bookTitleElement) {
            if (stripos($bookTitleElement->textContent, $searched) !== false) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);

    }
    public function testAuthorOneNameMatch()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Submit the form with some bad credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);


        // Simulate the selected option
        $selected = 'author';
        // Simulate the searched term
        $searched = 'Mark';

        // Make a request to the search page with the desired parameters
        $client->request('GET', '/search', [
            'searched' => $searched,
            'selected' => $selected,

        ]);
        $crawler = $client->request('GET', '/search', [
            'searched' => $searched,
            'selected' => $selected,
        ]);

        ///////////// WORD match

        // Use the filter method to select elements with class "bookTitle"
        $bookTitleElements = $crawler->filter('.bookAuthor');

        // Assert that the searched term is found in at least one of the "bookTitle" elements
        $found = false;
        foreach ($bookTitleElements as $bookTitleElement) {
            if (stripos($bookTitleElement->textContent, $searched) !== false) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);
    }
    public function testAuthorCharacterMatch()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Submit the form with some bad credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);


        // Simulate the selected option
        $selected = 'author';
        // Simulate the searched term
        $searched = 'Morf';

        // Make a request to the search page with the desired parameters
        $client->request('GET', '/search', [
            'searched' => $searched,
            'selected' => $selected,

        ]);
        $crawler = $client->request('GET', '/search', [
            'searched' => $searched,
            'selected' => $selected,
        ]);

        ///////////// WORD match

        // Use the filter method to select elements with class "bookTitle"
        $bookTitleElements = $crawler->filter('.bookAuthor');

        // Assert that at least one of the "bookTitle" elements has at least three corresponding characters with the searched term
        $found = false;
        foreach ($bookTitleElements as $bookTitleElement) {
            $title = $bookTitleElement->textContent;
            $correspondingCharacters = 0;

            // Compare each character in the title with the searched term
            for ($i = 0; $i < strlen($searched); $i++) {
                if (stripos($title, $searched[$i]) !== false) {
                    $correspondingCharacters++;
                }
            }

            if ($correspondingCharacters >= 3) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);
    }
    public function testISBNExactMatch()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Submit the form with some bad credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);


        // Simulate the selected option
        $selected = 'isbn';
        // Simulate the searched term
        $searched = '195153448';

        // Make a request to the search page with the desired parameters
        $client->request('GET', '/search', [
            'searched' => $searched,
            'selected' => $selected,

        ]);
        $crawler = $client->request('GET', '/search', [
            'searched' => $searched,
            'selected' => $selected,
        ]);

        ///////////// EXACT match
        $searched = 'Classical Mythology';

        // Use the filter method to select elements with class "bookTitle"
        $bookTitleElements = $crawler->filter('.bookTitle');

        // Assert that the searched term has an exact match with at least one of the "bookTitle" elements
        $found = false;
        foreach ($bookTitleElements as $bookTitleElement) {
            if ($bookTitleElement->textContent === $searched) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);

    }
    public function testISBNPartlyMatch()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Submit the form with some bad credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);


        // Simulate the selected option
        $selected = 'isbn';
        // Simulate the searched term
        $searched = '1951';

        // Make a request to the search page with the desired parameters
        $client->request('GET', '/search', [
            'searched' => $searched,
            'selected' => $selected,

        ]);
        $crawler = $client->request('GET', '/search', [
            'searched' => $searched,
            'selected' => $selected,
        ]);

        ///////////// CHAR match
        $searched = 'Classical Mythology';
        // Use the filter method to select elements with class "bookTitle"
        $bookTitleElements = $crawler->filter('.bookTitle');

        // Assert that at least one of the "bookTitle" elements has at least three corresponding characters with the searched term
        $found = false;
        foreach ($bookTitleElements as $bookTitleElement) {
            $title = $bookTitleElement->textContent;
            $correspondingCharacters = 0;

            // Compare each character in the title with the searched term
            for ($i = 0; $i < strlen($searched); $i++) {
                if (stripos($title, $searched[$i]) !== false) {
                    $correspondingCharacters++;
                }
            }

            if ($correspondingCharacters >= 3) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);
    }

    /////MYBOOKS/////
    public function testShowMyBooksPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Submit the form with good credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);

        // test e.g. the MyBooks page
        $client->request('GET', '/myBooks');
        $this->assertResponseIsSuccessful();

        // Assert other necessary assertions based on the expected behavior of the page
        // For example, you can assert that certain elements are present on the rendered page.
        $this->assertSelectorExists('#beaned');
        $this->assertSelectorExists('#planted');
        $this->assertSelectorExists('#finished');


    }

    //////TRENDING//////
    public function testShowTrendingPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Submit the form with some bad credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);

        // test e.g. the profile page
        $client->request('GET', '/trending');
        $crawler = $client->request('GET', '/trending');
        $this->assertResponseIsSuccessful();


        // Assert that the response contains the expected content
        $this->assertStringContainsString('Trending', $crawler->filter('#Title')->text());
        // Add more assertions based on your specific expectations
        // Assert that the response contains the top books
        $this->assertCount(10, $crawler->filter('.OneBook'));
        // Assert that the response contains the expected content
        $this->assertStringContainsString('Trending', $crawler->filter('#Title')->text());
        // Add more assertions based on your specific expectations
        // Assert that the response contains the top books
        $this->assertCount(10, $crawler->filter('.OneBook'));

    }
    public function testOneBook(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Submit the form with some bad credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);

        // test e.g. the profile page
        $client->request('GET', '/trending');
        $crawler = $client->request('GET', '/trending');
        $this->assertResponseIsSuccessful();

        // For example, assert that each book contains a title
        $crawler->filter('.OneBook')->each(function ($bookNode) {
            $this->assertGreaterThan(0, $bookNode->filter('h3')->count());
        });

        // Assert that each book has a link to open the book page
        $crawler->filter('.OneBook')->each(function ($bookNode) {
            $this->assertGreaterThan(0, $bookNode->filter('a.bookButton')->count());
        });

        // Assert that each book has a rating system with bean buttons
        $crawler->filter('.OneBook')->each(function ($bookNode) {
            $this->assertGreaterThan(0, $bookNode->filter('.ratingSystem .rating .BeanButton')->count());
        });
    }
    public function testGenresBooks(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Submit the form with some bad credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);

        // test e.g. the profile page
        $client->request('GET', '/trending');
        $crawler = $client->request('GET', '/trending');
        $this->assertResponseIsSuccessful();


        // Create an array of valid genres
        $expectedGenres = [
            'Historical Fiction',
            'Mystery',
            'Science Fiction',
            'Fantasy',
            'Romance',
            'Thriller',
            'Horror',
            'Biography',
            'Memoir',
            'Young Adult',
            'Dystopian',
            'Adventure',
            'Crime',
            'Comedy',
            'Self-help',
            'Satire',
            'Poetry',
            'Western',
            'Graphic Novel',
            'Travelogue'
        ];

        // Loop through each book entry and check its genres
        $crawler->filter('.OneBook')->each(function ($bookEntry) use ($expectedGenres) {
            $genres = $bookEntry->filter('.bookGenre')->each(function ($genreNode) {
                return $genreNode->text();
            });

            // Assert that each genre is in the expected genres
            foreach ($genres as $genre) {
                $this->assertContains($genre, $expectedGenres);
            }
        });
    }

    ///// BOOK //////
    public function testShowBookPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');


        // Submit the form with some bad credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);


        // test e.g. the profile page
        $client->request('GET', '/book');
        $this->assertResponseIsSuccessful();
    }
    public function testRenderBookPageWithValidBookId()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');


        // Submit the form with valid credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);


        // Test rendering the book page with a valid bookId
        $client->request('GET', '/book?bookId=1');
        $this->assertResponseIsSuccessful();
        // Add assertions to check if the correct book details are displayed
        $this->assertSelectorTextContains('#description h1', 'Classical Mythology');
        // Add more assertions as needed
    }
    public function testRenderBookPageWithInvalidBookId()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Submit the form with valid credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);

        // Test rendering the book page with an invalid bookId
        $client->request('GET', '/book?bookId=9999');
        // Add assertions to check if a default book is displayed or handle the invalid bookId gracefully
        $this->assertSelectorTextContains('#description h1', 'Classical Mythology');
        // Add more assertions as needed
    }
    public function testReviewSubmission()
    {
        //This part logs in to a validated user
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');


        // Submit the form with valid credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);


        //this part goes to the first book
        $crawler = $client->request('GET', '/book');


        $form = $crawler->selectButton('Submit')->form([
            'review' => 'This is my review text.'
        ]);
        $client->submit($form);
        $commentToFind = 'This is my review text.';


        $crawler = $client->reload();


        // Assert that the review was submitted successfully
        $this->assertEquals(200, $client->getResponse()->getStatusCode());


        $reviewList = $crawler->filter('ul')->first(); // Get the first <ul> element
        $foundComment = false;


        // Iterate over each <li> element within the review list
        foreach ($reviewList->children() as $reviewItem) {
            $comment = $reviewItem->nodeValue; // Get the text content of the <li> element
            echo $comment . "\n";
            // Check if the comment matches the one we are looking for
            if (str_contains($comment, $commentToFind) !== false) {
                $foundComment = true;
                break;
            }
        }
        // Assert that the comment was found in the review list
        $this->assertTrue($foundComment);
    }
    public function testGreenButton()
    {
        //This part logs in to a validated user
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');


        // Submit the form with valid credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);


        for ($i = 0; $i < 2; $i++) {
            echo("\n" . 'Loop Counter: ' . $i + 1 . "\n\n");


            //this part goes to the first book
            $crawler = $client->request('GET', '/book');


            $beanCounterBeforePress = $crawler->filter('.greenCounter')->text();
            echo("beanCounterBeforePress: " . $beanCounterBeforePress . "\n");


            $crawler = $client->request('GET', '/book');
            $image = $crawler->filter('img[alt="green button on"]')->first();
            try {
                $form = $image->closest('form')->form([
                    'bookIdByGreenBean' => 1
                ]);
                $greenbutton = 1; // green button is on (aka beaned)
                echo("FOUND: greenButton: ON\n");
            } catch (\InvalidArgumentException $e) {
                echo("NOT FOUND: greenButton: ON \n");
            }
            $crawler = $client->request('GET', '/book');
            $image = $crawler->filter('img[alt="green button off"]')->first();
            try {
                $form = $image->closest('form')->form([
                    'bookIdByGreenBean' => 1
                ]);
                echo("FOUND: greenButton: OFF \n");
                $greenbutton = 0; //button is off (aka not beaned)
            } catch (\InvalidArgumentException $e) {
                echo("NOT FOUND: greenButton: OFF \n");
            }
            echo("Green button: " . $greenbutton . "\n");
            $client->submit($form);


            $crawler = $client->request('GET', '/book');
            $beanCounterAfterPress = $crawler->filter('.greenCounter')->text();
            echo("beanCounterAfterPress: " . $beanCounterAfterPress . "\n\n");


            // Assert that the comment was found in the review list
            if ($greenbutton == 1) {
                $this->assertEquals(intval($beanCounterAfterPress), intval($beanCounterBeforePress) - 1);
            } else {
                $this->assertEquals(intval($beanCounterAfterPress), intval($beanCounterBeforePress) + 1);
            }
        }
    }
    public function testRedButton()
    {
        //This part logs in to a validated user
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');


        // Submit the form with valid credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);


        for ($i = 0; $i < 2; $i++) {
            echo("\n" . 'Loop Counter: ' . $i + 1 . "\n\n");


            //this part goes to the first book
            $crawler = $client->request('GET', '/book');


            $beanCounterBeforePress = $crawler->filter('.redCounter')->text();
            echo("beanCounterBeforePress: " . $beanCounterBeforePress . "\n");


            $crawler = $client->request('GET', '/book');
            $image = $crawler->filter('img[alt="red button on"]')->first();
            try {
                $form = $image->closest('form')->form([
                    'bookIdByRedBean' => 1
                ]);
                $redButton = 1; // red button is on (aka beaned)
                echo("FOUND: redButton: ON\n");
            } catch (\InvalidArgumentException $e) {
                echo("NOT FOUND: redButton: ON \n");
            }
            $crawler = $client->request('GET', '/book');
            $image = $crawler->filter('img[alt="red button off"]')->first();
            try {
                $form = $image->closest('form')->form([
                    'bookIdByRedBean' => 1
                ]);
                echo("FOUND: redButton: OFF \n");
                $redButton = 0; //button is off (aka not beaned)
            } catch (\InvalidArgumentException $e) {
                echo("NOT FOUND: redButton: OFF \n");
            }
            echo("Red button: " . $redButton . "\n");
            $client->submit($form);


            $crawler = $client->request('GET', '/book');
            $redBeanCounterAfterPress = $crawler->filter('.redCounter')->text();
            echo("beanCounterAfterPress: " . $redBeanCounterAfterPress . "\n\n");


            // Assert that the comment was found in the review list
            if ($redButton == 1) {
                $this->assertEquals(intval($redBeanCounterAfterPress), intval($beanCounterBeforePress) - 1);
            } else {
                $this->assertEquals(intval($redBeanCounterAfterPress), intval($beanCounterBeforePress) + 1);
            }
        }
    }
    public function testPlantButton()
    {
        //This part logs in to a validated user
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');


        // Submit the form with valid credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);


        for ($i = 0; $i < 2; $i++) {
            echo("\n" . 'Loop Counter: ' . $i + 1 . "\n\n");


            //this part goes to the first book
            $crawler = $client->request('GET', '/book');


            $plantCounterBeforePress = $crawler->filter('.plantCounter')->text();
            echo("beanCounterBeforePress: " . $plantCounterBeforePress . "\n");


            $crawler = $client->request('GET', '/book');
            $image = $crawler->filter('img[alt="plant button on"]')->first();
            try {
                $form = $image->closest('form')->form([
                    'bookIdByPlant' => 1
                ]);
                $plantButton = 1; // plant button is on (aka beaned)
                echo("FOUND: plantButton: ON\n");
            } catch (\InvalidArgumentException $e) {
                echo("NOT FOUND: plantButton: ON \n");
            }
            $crawler = $client->request('GET', '/book');
            $image = $crawler->filter('img[alt="plant button off"]')->first();
            try {
                $form = $image->closest('form')->form([
                    'bookIdByPlant' => 1
                ]);
                echo("FOUND: plantButton: OFF \n");
                $plantButton = 0; //button is off (aka not beaned)
            } catch (\InvalidArgumentException $e) {
                echo("NOT FOUND: plantButton: OFF \n");
            }
            echo("Plant button: " . $plantButton . "\n");
            $client->submit($form);


            $crawler = $client->request('GET', '/book');
            $plantCounterAfterPress = $crawler->filter('.plantCounter')->text();
            echo("beanCounterAfterPress: " . $plantCounterAfterPress . "\n\n");


            // Assert that the comment was found in the review list
            if ($plantButton == 1) {
                $this->assertEquals(intval($plantCounterAfterPress), intval($plantCounterBeforePress) - 1);
            } else {
                $this->assertEquals(intval($plantCounterAfterPress), intval($plantCounterBeforePress) + 1);
            }
        }
    }
    public function testFinishedButton()
    {
        //This part logs in to a validated user
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');


        // Submit the form with valid credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'web.technology.project.2023@gmail.com',
            '_password' => 'Qwerty123!',
        ]);
        $client->submit($form);


        for ($i = 0; $i < 2; $i++) {
            echo("\n" . 'Loop Counter: ' . $i + 1 . "\n\n");


            //this part goes to the first book
            $crawler = $client->request('GET', '/book');


            $finishedCounterBeforePress = $crawler->filter('.finishedCounter')->text();
            echo("finishedCounterBeforePress: " . $finishedCounterBeforePress . "\n");


            $crawler = $client->request('GET', '/book');
            $image = $crawler->filter('img[alt="finished button on"]')->first();
            try {
                $form = $image->closest('form')->form([
                    'bookIdByFinished' => 1
                ]);
                $finishedButton = 1; // finished button is on (aka beaned)
                echo("FOUND: finishedButton: ON\n");
            } catch (\InvalidArgumentException $e) {
                echo("NOT FOUND: finishedButton: ON \n");
            }
            $crawler = $client->request('GET', '/book');
            $image = $crawler->filter('img[alt="finished button off"]')->first();
            try {
                $form = $image->closest('form')->form([
                    'bookIdByFinished' => 1
                ]);
                echo("FOUND: finishedButton: OFF \n");
                $finishedButton = 0; //button is off (aka not beaned)
            } catch (\InvalidArgumentException $e) {
                echo("NOT FOUND: finishedButton: OFF \n");
            }
            echo("Finished button: " . $finishedButton . "\n");
            $client->submit($form);


            $crawler = $client->request('GET', '/book');
            $finishedCounterAfterPress = $crawler->filter('.finishedCounter')->text();
            echo("beanCounterAfterPress: " . $finishedCounterAfterPress . "\n\n");


            // Assert that the comment was found in the review list
            if ($finishedButton == 1) {
                $this->assertEquals(intval($finishedCounterAfterPress), intval($finishedCounterBeforePress) - 1);
            } else {
                $this->assertEquals(intval($finishedCounterAfterPress), intval($finishedCounterBeforePress) + 1);
            }
        }
    }

    /////BOOK ENTITY/////
    public function testSetTitle()
    {
        $book = new Book();
        $title = 'Test Book Title';

        $book->setTitle($title);

        $this->assertEquals($title, $book->getTitle());
    }
    public function testSetAuthor()
    {
        $book = new Book();
        $author = 'John Doe';

        $book->setAuthor($author);

        $this->assertEquals($author, $book->getAuthor());
    }
    public function testSetBeans()
    {
        $book = new Book();
        $beans = 10;

        $book->setBeans($beans);

        $this->assertEquals($beans, $book->getBeans());
    }
    public function testAddBookGenre()
    {
        $book = new Book();
        $bookGenre = new BookGenres();
        $genre = new Genre();
        $genre->setGenre('adventure');
        $bookGenre->setGenre($genre);
        $book->addBookGenre($bookGenre);

        // Since `getBookGenres()` returns a collection, you need to access the first item in the collection to get the genre
        $firstBookGenre = $book->getBookGenres()->first();
        $this->assertEquals('adventure', $firstBookGenre->getGenre()->getGenre());
    }
    public function testRemoveBookGenre()
    {
        // Create a Book instance
        $book = new Book();

        // Create a Genre instance and set its genre
        $genre = new Genre();
        $genre->setGenre('adventure');

        // Create a BookGenres instance and associate it with the Genre
        $bookGenre = new BookGenres();
        $bookGenre->setGenre($genre);

        // Add the BookGenres to the Book
        $book->addBookGenre($bookGenre);

        // Assert that the BookGenres exists in the Book before removal
        $this->assertCount(1, $book->getBookGenres());

        // Remove the BookGenres from the Book
        $book->removeBookGenre($bookGenre);

        // Assert that the BookGenres has been removed from the Book
        $this->assertCount(0, $book->getBookGenres());
    }
}