<?php

namespace App\Tests\SecurityTests;

use App\Repository\UserRepository;
use App\Security\EmailVerificationChecker;
use App\Tests\BaseTestCaseSetUp;
use DOMDocument;
use DOMXPath;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\User\UserInterface;

class EmailVerificationTest extends BaseTestCaseSetUp
{
    public function testEmailVerification(){
        $this->submitRegistrationForm();

        $email_sent = $this->getMailerEvent()->getMessage();
        $this->assertInstanceOf(Email::class, $email_sent);
        $emailContent = $email_sent->getHtmlBody();

        $dom = new DOMDocument();
        @$dom->loadHTML($emailContent);
        $xpath = new DOMXPath($dom);

        $verificationUrl = $xpath->evaluate('string(//a/@href)');
        $this->client->request('GET', $verificationUrl);

        $container = $this->client->getContainer();
        $userRepository = $container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => $this->email]);
        $this->assertTrue($user->isVerified());
    }

    public function testCheckPreAuthWithNonUserInterface()
    {
        $checker = new EmailVerificationChecker();

        $user = $this->createMock(UserInterface::class);

        $checker->checkPreAuth($user);

        $this->assertTrue(true);
    }

    public function testHandleEmailConfirmationFail()
    {
        $this->submitRegistrationForm();

        $email_sent = $this->getMailerEvent()->getMessage();
        $this->assertInstanceOf(Email::class, $email_sent);
        $emailContent = $email_sent->getHtmlBody();

        $dom = new DOMDocument();
        @$dom->loadHTML($emailContent);
        $xpath = new DOMXPath($dom);

        $verificationUrl = $xpath->evaluate('string(//a/@href)');

        //Corrupt verification URL (Replace occurances of letter 'D' with 'Q')
        $verificationUrl = str_replace('D', 'Q', $verificationUrl);

        $this->client->request('GET', $verificationUrl);

        $container = $this->client->getContainer();
        $userRepository = $container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => $this->email]);
        $this->assertFalse($user->isVerified());
    }
}