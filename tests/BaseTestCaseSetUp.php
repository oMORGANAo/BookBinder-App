<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseTestCaseSetUp extends WebTestCase
{
    protected string $email;
    protected \Symfony\Bundle\FrameworkBundle\KernelBrowser $client;
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->email = 'test' . uniqid() . '@example.com';
    }

    protected function submitRegistrationForm($firstName = 'Andrei', $surname = 'Trafimau', $postalCode = '3001', $birthdate = '2000-01-01', $password = 'Password123345!'): void
    {
        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('Sign Up')->form();
        $form['registration_form[email]'] = $this->email;
        $form['registration_form[firstName]'] = $firstName;
        $form['registration_form[surname]'] = $surname;
        $form['registration_form[postalCode]'] = $postalCode;
        $form['registration_form[birthdate]'] = $birthdate;
        $form['registration_form[plainPassword][first]'] = $password;
        $form['registration_form[plainPassword][second]'] = $password;

        $this->client->submit($form);
    }
}