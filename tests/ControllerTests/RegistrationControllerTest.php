<?php

namespace App\Tests\ControllerTests;

use App\Tests\BaseTestCaseSetUp;

class RegistrationControllerTest extends BaseTestCaseSetUp
{

    public function testShowRegistrationForm(): void
    {
        $this->client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Sign Up');
    }

    public function testRegister(): void
    {
        $this->submitRegistrationForm();

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $expectedUrl = '/confirm-email';
        $this->assertEquals($expectedUrl, $this->client->getResponse()->getTargetUrl());
    }

    public function testConfirmEmail(): void
    {
        $this->submitRegistrationForm();
        $this->client->request('GET', '/confirm-email');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Please confirm your email');
    }

    public function testConfirmedEmail(): void
    {
        $this->client->request('GET', '/successful_confirm');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Success!');
    }

    public function testInvalidEmailRegister(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('Sign Up')->form();
        $form['registration_form[email]'] = 'wrong_email';
        $form['registration_form[firstName]'] = 'Andrei';
        $form['registration_form[surname]'] = 'Trafimau';
        $form['registration_form[postalCode]'] = '3001';
        $form['registration_form[birthdate]'] = '2000-01-01';
        $form['registration_form[plainPassword][first]'] = 'Password123345!';
        $form['registration_form[plainPassword][second]'] = 'Password123345!';

        $this->client->submit($form);

        $this->assertFalse($this->client->getResponse()->isRedirect());
    }

    public function testPasswordNotMatchRegister(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('Sign Up')->form();
        $form['registration_form[email]'] = $this->email;
        $form['registration_form[firstName]'] = 'Andrei';
        $form['registration_form[surname]'] = 'Trafimau';
        $form['registration_form[postalCode]'] = '3001';
        $form['registration_form[birthdate]'] = '2000-01-01';
        $form['registration_form[plainPassword][first]'] = 'Password123345!';
        $form['registration_form[plainPassword][second]'] = 'password123345!';

        $this->client->submit($form);

        $this->assertFalse($this->client->getResponse()->isRedirect());
    }


    public function testExistingEmailRegister(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('Sign Up')->form();
        $form['registration_form[email]'] = 'spulska.tv@gmail.com';
        $form['registration_form[firstName]'] = 'Andrei';
        $form['registration_form[surname]'] = 'Trafimau';
        $form['registration_form[postalCode]'] = '3001';
        $form['registration_form[birthdate]'] = '2000-01-01';
        $form['registration_form[plainPassword][first]'] = 'Password123345!';
        $form['registration_form[plainPassword][second]'] = 'Password123345!';

        $this->client->submit($form);

        $this->assertFalse($this->client->getResponse()->isRedirect());
    }

    public function testPasswordLengthRegister(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('Sign Up')->form();
        $form['registration_form[email]'] = $this->email;
        $form['registration_form[firstName]'] = 'Andrei';
        $form['registration_form[surname]'] = 'Trafimau';
        $form['registration_form[postalCode]'] = '3001';
        $form['registration_form[birthdate]'] = '2000-01-01';
        $form['registration_form[plainPassword][first]'] = 'Pa123!';
        $form['registration_form[plainPassword][second]'] = 'Pa123!';

        $this->client->submit($form);

        $this->assertFalse($this->client->getResponse()->isRedirect());
    }

    public function testPasswordSpecialCharRegister(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('Sign Up')->form();
        $form['registration_form[email]'] = $this->email;
        $form['registration_form[firstName]'] = 'Andrei';
        $form['registration_form[surname]'] = 'Trafimau';
        $form['registration_form[postalCode]'] = '3001';
        $form['registration_form[birthdate]'] = '2000-01-01';
        $form['registration_form[plainPassword][first]'] = 'Password123345';
        $form['registration_form[plainPassword][second]'] = 'Password123345';

        $this->client->submit($form);

        $this->assertFalse($this->client->getResponse()->isRedirect());
    }
    public function testPasswordUpperCaseRegister(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('Sign Up')->form();
        $form['registration_form[email]'] = $this->email;
        $form['registration_form[firstName]'] = 'Andrei';
        $form['registration_form[surname]'] = 'Trafimau';
        $form['registration_form[postalCode]'] = '3001';
        $form['registration_form[birthdate]'] = '2000-01-01';
        $form['registration_form[plainPassword][first]'] = 'password123!';
        $form['registration_form[plainPassword][second]'] = 'password123!';

        $this->client->submit($form);

        $this->assertFalse($this->client->getResponse()->isRedirect());
    }
    public function testPasswordLowerCaseRegister(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('Sign Up')->form();
        $form['registration_form[email]'] = $this->email;
        $form['registration_form[firstName]'] = 'Andrei';
        $form['registration_form[surname]'] = 'Trafimau';
        $form['registration_form[postalCode]'] = '3001';
        $form['registration_form[birthdate]'] = '2000-01-01';
        $form['registration_form[plainPassword][first]'] = 'PASSWORD123345!';
        $form['registration_form[plainPassword][second]'] = 'PASSWORD123345!';

        $this->client->submit($form);

        $this->assertFalse($this->client->getResponse()->isRedirect());
    }

    public function testPasswordNoDigitsRegister(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('Sign Up')->form();
        $form['registration_form[email]'] = $this->email;
        $form['registration_form[firstName]'] = 'Andrei';
        $form['registration_form[surname]'] = 'Trafimau';
        $form['registration_form[postalCode]'] = '3001';
        $form['registration_form[birthdate]'] = '2000-01-01';
        $form['registration_form[plainPassword][first]'] = 'Password!';
        $form['registration_form[plainPassword][second]'] = 'Password!';

        $this->client->submit($form);

        $this->assertFalse($this->client->getResponse()->isRedirect());
    }
}