<?php

namespace App\Tests\UnitTests;

use App\Form\ResetPasswordRequestFormType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
class ResetPasswordRequestFormTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
    {
        $validator = Validation::createValidator();
        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testSubmitValidEmail()
    {
        $formData = [
            'email' => 'valid@email.com',
        ];

        $form = $this->factory->create(ResetPasswordRequestFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
    }

    public function testSubmitBlankEmail()
    {
        $formData = [
            'email' => '',
        ];

        $form = $this->factory->create(ResetPasswordRequestFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitInvalidEmail()
    {
        $formData = [
            'email' => 'ivalidEmail',
        ];

        $form = $this->factory->create(ResetPasswordRequestFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }
    public function testSubmitInvalidEmail2()
    {
        $formData = [
            'email' => 'invalidEmail@',
        ];

        $form = $this->factory->create(ResetPasswordRequestFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }
    public function testSubmitInvalidEmail3()
    {
        $formData = [
            'email' => 'invalid@Email',
        ];

        $form = $this->factory->create(ResetPasswordRequestFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitInvalidEmail4()
    {
        $formData = [
            'email' => 'invalid@Email.',
        ];

        $form = $this->factory->create(ResetPasswordRequestFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

}