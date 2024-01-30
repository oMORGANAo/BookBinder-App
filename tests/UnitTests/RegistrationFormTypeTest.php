<?php

namespace App\Tests\UnitTests;

use App\Form\RegistrationFormType;
use App\Entity\User;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class RegistrationFormTypeTest extends TypeTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->email = 'test' . uniqid() . '@example.com';

        $passwordHashed = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHashed->method('hashPassword')->willReturn('hashedPassword');
        parent::setUp();
    }

    protected function getExtensions(): array
    {
        return [
            new ValidatorExtension(Validation::createValidator()),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'email' => $this->email,
            'firstName' => 'Andrei',
            'surname' => 'Trafimau',
            'postalCode' => '3001',
            'birthdate' => '2000-01-01',
            'plainPassword' => [
                'first' => 'Test123!',
                'second' => 'Test123!',
            ],
        ];

        $object = new User();
        $form = $this->factory->create(RegistrationFormType::class, $object);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($formData['email'], $form->get('email')->getData());
        $this->assertEquals($formData['firstName'], $form->get('firstName')->getData());
        $this->assertEquals($formData['surname'], $form->get('surname')->getData());
        $this->assertEquals($formData['postalCode'], $form->get('postalCode')->getData());
        $this->assertEquals($formData['birthdate'], $form->get('birthdate')->getData()->format('Y-m-d'));
        $this->assertEquals($formData['plainPassword']['first'], $form->get('plainPassword')->get('first')->getData());
        $this->assertEquals($formData['plainPassword']['second'], $form->get('plainPassword')->get('second')->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function testSubmitInvalidFirstName()
    {
        $formData = [
            'email' => $this->email,
            'firstName' => 'andrei',
            'surname' => 'Trafimau',
            'postalCode' => '3001',
            'birthdate' => '2000-01-01',
            'plainPassword' => [
                'first' => 'Test123!',
                'second' => 'Test123!',
            ],
        ];

        $object = new User();
        $form = $this->factory->create(RegistrationFormType::class, $object);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitInvalidSurname()
    {
        $formData = [
            'email' => $this->email,
            'firstName' => 'Andrei',
            'surname' => 'trafimau',
            'postalCode' => '3001',
            'birthdate' => '2000-01-01',
            'plainPassword' => [
                'first' => 'Test123!',
                'second' => 'Test123!',
            ],
        ];

        $object = new User();
        $form = $this->factory->create(RegistrationFormType::class, $object);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitInvalidPostalCode()
    {
        $formData = [
            'email' => $this->email,
            'firstName' => 'Andrei',
            'surname' => 'Trafimau',
            'postalCode' => '300',
            'birthdate' => '2000-01-01',
            'plainPassword' => [
                'first' => 'Test123!',
                'second' => 'Test123!',
            ],
        ];

        $object = new User();
        $form = $this->factory->create(RegistrationFormType::class, $object);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitInvalidPassword()
    {
        $formData = [
            'email' => $this->email,
            'firstName' => 'Andrei',
            'surname' => 'Trafimau',
            'postalCode' => '3001',
            'birthdate' => '2000-01-01',
            'plainPassword' => [
                'first' => 'Test123',
                'second' => 'Test123',
            ],
        ];

        $object = new User();
        $form = $this->factory->create(RegistrationFormType::class, $object);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitNotMatchingPasswords()
    {
        $formData = [
            'email' => $this->email,
            'firstName' => 'Andrei',
            'surname' => 'Trafimau',
            'postalCode' => '3001',
            'birthdate' => '2000-01-01',
            'plainPassword' => [
                'first' => 'Test123!',
                'second' => 'Test123',
            ],
        ];

        $object = new User();
        $form = $this->factory->create(RegistrationFormType::class, $object);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }
}