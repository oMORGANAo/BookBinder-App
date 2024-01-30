<?php

namespace App\Tests\ControllerTests;

use App\Repository\UserRepository;
use App\Tests\BaseTestCaseSetUp;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class ResetPasswordControllerTest extends BaseTestCaseSetUp
{
    public function testRequestPage()
    {
        $crawler = $this->client->request('GET', '/reset-password');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Reset your password', $crawler->filter('h2')->text());
    }

    public function testSubmitValidEmail()
    {
        $crawler = $this->client->request('GET', '/reset-password');

        $form = $crawler->selectButton('Send password reset email')->form();
        $form['reset_password_request_form[email]'] = 'test@example.com';
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect('/reset-password/check-email'));
        $this->client->followRedirect();

        $title = 'Password Reset Email Sent';
        $this->assertStringContainsString($title, $this->client->getResponse()->getContent());
    }

    public function testSubmitInvalidEmail()
    {
        $crawler = $this->client->request('GET', '/reset-password');

        $form = $crawler->selectButton('Send password reset email')->form();
        $form['reset_password_request_form[email]'] = 'invalid-email';
        $this->client->submit($form);

        $this->assertStringContainsString('Please enter a valid email address', $this->client->getResponse()->getContent());
    }

    public function testResetPasswordWithValidTokenBadPassword1()
    {
        $this->submitRegistrationForm();
        $container = $this->client->getContainer();

        $userRepository = $container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => $this->email]);

        if (!$user) {
            $this->markTestSkipped('User not found.');
        }

        $resetPasswordHelper = $container->get(ResetPasswordHelperInterface::class);
        $resetToken = $resetPasswordHelper->generateResetToken($user);

        $this->client->request('GET', '/reset-password/reset/'.$resetToken->getToken());

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/reset-password/reset');

        $form = $crawler->selectButton('Reset password')->form();

        $form['change_password_form[plainPassword][first]'] = 'InvalidPassword123';
        $form['change_password_form[plainPassword][second]'] = 'InvalidPassword123';

        $this->client->submit($form);

        $this->assertFalse($this->client->getResponse()->isRedirect());
    }

    public function testResetPasswordWithValidTokenBadPassword2()
    {
        $this->submitRegistrationForm();
        $container = $this->client->getContainer();

        $userRepository = $container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => $this->email]);

        if (!$user) {
            $this->markTestSkipped('User not found.');
        }

        $resetPasswordHelper = $container->get(ResetPasswordHelperInterface::class);
        $resetToken = $resetPasswordHelper->generateResetToken($user);

        $this->client->request('GET', '/reset-password/reset/'.$resetToken->getToken());

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/reset-password/reset');

        $form = $crawler->selectButton('Reset password')->form();

        $form['change_password_form[plainPassword][first]'] = 'InvalidPassword!';
        $form['change_password_form[plainPassword][second]'] = 'InvalidPassword!';

        $this->client->submit($form);

        $this->assertFalse($this->client->getResponse()->isRedirect());
    }

    public function testResetPasswordWithValidTokenBadPassword3()
    {
        $this->submitRegistrationForm();
        $container = $this->client->getContainer();

        $userRepository = $container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => $this->email]);

        if (!$user) {
            $this->markTestSkipped('User not found.');
        }

        $resetPasswordHelper = $container->get(ResetPasswordHelperInterface::class);
        $resetToken = $resetPasswordHelper->generateResetToken($user);

        $this->client->request('GET', '/reset-password/reset/'.$resetToken->getToken());

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/reset-password/reset');

        $form = $crawler->selectButton('Reset password')->form();

        $form['change_password_form[plainPassword][first]'] = 'invalidpassword123!';
        $form['change_password_form[plainPassword][second]'] = 'invalidpassword123!';

        $this->client->submit($form);

        $this->assertFalse($this->client->getResponse()->isRedirect());
    }

    public function testResetPasswordWithValidTokenGoodPassword()
    {
        $this->submitRegistrationForm();
        $container = $this->client->getContainer();

        $userRepository = $container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => $this->email]);

        if (!$user) {
            $this->markTestSkipped('User not found.');
        }

        $resetPasswordHelper = $container->get(ResetPasswordHelperInterface::class);
        $resetToken = $resetPasswordHelper->generateResetToken($user);

        $this->client->request('GET', '/reset-password/reset/'.$resetToken->getToken());

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/reset-password/reset');

        $form = $crawler->selectButton('Reset password')->form();

        $form['change_password_form[plainPassword][first]'] = 'ValidPassword123!';
        $form['change_password_form[plainPassword][second]'] = 'ValidPassword123!';

        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/reset-password/successful_reset'));
    }

    public function testResetPasswordWithInvalidToken()
    {
        $this->client->request('GET', '/reset-password/reset/invalid-token');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->assertTrue($this->client->getResponse()->isRedirect('/reset-password/reset'));

        $this->client->followRedirect();
    }

    public function testSendResetPasswordEmail()
    {
        $this->submitRegistrationForm();
        $container = $this->client->getContainer();

        $userRepository = $container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => $this->email]);

        if (!$user) {
            $this->markTestSkipped('User not found.');
        }

        $email = (new TemplatedEmail())
            ->htmlTemplate('reset_password/email.html.twig')
        ;

        $crawler = $this->client->request('GET', '/reset-password');

        $form = $crawler->selectButton('Send password reset email')->form();
        $form['reset_password_request_form[email]'] = $user->getEmail();
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect('/reset-password/check-email'));
        $this->client->followRedirect();

        $email = unserialize(serialize($email));
        $this->assertEquals('reset_password/email.html.twig', $email->getHtmlTemplate());
        $title = 'Password Reset Email Sent';
        $this->assertStringContainsString($title, $this->client->getResponse()->getContent());
    }

    public function testResetedPassword()
    {
        $this->client->request('GET', '/reset-password/successful_reset');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsString('reseted_password.css', $this->client->getResponse()->getContent());
    }

}