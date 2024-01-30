<?php

namespace App\Tests\UnitTests;

use App\Form\ChangePasswordFormType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class ChangePasswordFormTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
    {
        $validator = Validation::createValidator();
        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'Password123321!',
                'second' => 'Password123321!',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
    }

    public function testSubmitValidData2()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'QwertyYtrewq1*!',
                'second' => 'QwertyYtrewq1*!',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
    }

    public function testSubmitInvalidDataNoCups()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'password123321!',
                'second' => 'password123321!',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitInvalidDataNoSpecialCharacters()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'Password123321',
                'second' => 'Password123321',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }
    public function testSubmitInvalidDataNoNumbers()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'Password!',
                'second' => 'Password!',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }
    public function testSubmitInvalidDataNoLowercase()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'PASSWORD123321!',
                'second' => 'PASSWORD123321!',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitShortPassword()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'Pss123!',
                'second' => 'Pss123!',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitMismatchedPasswords()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'Password123321!',
                'second' => 'Password123321',  //No Special Character
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitMismatchedPasswords2()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'Password123321!',
                'second' => 'Password123321*',  //Different Special Character
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitMismatchedPasswords3()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'Password123321!',
                'second' => 'password123321!', //Uppercase letter changed to lowercase
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }
}