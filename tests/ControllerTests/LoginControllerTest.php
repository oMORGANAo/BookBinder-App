<?php

namespace App\Tests\ControllerTests;

use App\Tests\BaseTestCaseSetUp;

class LoginControllerTest extends BaseTestCaseSetUp
{

    public function testShowHomePage()
    {
        $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Hello, User!');
    }
    public function testShowLoginPage()
    {
        $this->client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Login');
    }

    public function testLoginWithBadCredentials()
    {
        $crawler = $this->client->request('GET', '/login');

        // Submit the form with some bad credentials
        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'wrong_username@mail.com',
            '_password' => 'wrong_password',
        ]);
        $this->client->submit($form);

        $this->assertResponseRedirects('http://localhost/login');
        $this->client->followRedirect();

        // Assert the login error message is displayed
        $this->assertSelectorTextContains('.error-message', 'Invalid credentials.');
    }

    public function testLoginWithBadCredentials2()
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'wrong_username',
            '_password' => 'wrong_password',
        ]);
        $this->client->submit($form);

        $this->assertResponseRedirects('http://localhost/login');

        $this->client->followRedirect();
        $this->assertSelectorTextContains('h2', 'Login');

        $this->assertFalse($this->client->getContainer()->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'));
    }

    public function testLoginWithoutAuthentication()
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'pwillmott1@omniture.com',
            '_password' => 'RGL98h',
        ]);
        $this->client->submit($form);

        $this->assertResponseRedirects('http://localhost/login');
        $this->client->followRedirect();

        $this->assertSelectorTextContains('.error-message', 'Please verify your email before logging in.');
    }


    public function testLoginWithGoodCredentialsAndAuthentication()
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Log In')->form([
            '_username' => 'spulska.tv@gmail.com',
            '_password' => 'Password123321!',
        ]);
        $this->client->submit($form);

        $this->assertResponseRedirects('http://localhost/my_profile');
    }
}
