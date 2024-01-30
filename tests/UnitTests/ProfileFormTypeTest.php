<?php

namespace App\Tests\UnitTests;

use Symfony\Component\Form\Test\TypeTestCase;
use App\Form\ProfileFormType;
use App\Entity\User;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;

class ProfileFormTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
    {
        return [
            new ValidatorExtension(Validation::createValidator()),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'firstName' => 'Andrei',
            'surname' => 'Trafimau',
            'postalCode' => '3001',
            'birthdate' => '2000-01-01',
        ];

        $object = new User();
        $form = $this->factory->create(ProfileFormType::class, $object);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($formData['firstName'], $form->get('firstName')->getData());
        $this->assertEquals($formData['surname'], $form->get('surname')->getData());
        $this->assertEquals($formData['postalCode'], $form->get('postalCode')->getData());
        $this->assertEquals($formData['birthdate'], $form->get('birthdate')->getData()->format('Y-m-d'));

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function testSubmitInvalidFirstName()
    {
        $formData = [
            'firstName' => 'andrei',
            'surname' => 'Trafimau',
            'postalCode' => '3001',
            'birthdate' => '2000-01-01',
        ];

        $object = new User();
        $form = $this->factory->create(ProfileFormType::class, $object);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitInvalidSurname()
    {
        $formData = [
            'firstName' => 'Andrei',
            'surname' => 'trafimau',
            'postalCode' => '3001',
            'birthdate' => '2000-01-01',
        ];

        $object = new User();
        $form = $this->factory->create(ProfileFormType::class, $object);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitInvalidPostalCode()
    {
        $formData = [
            'firstName' => 'Andrei',
            'surname' => 'Trafimau',
            'postalCode' => '300',
            'birthdate' => '2000-01-01',
        ];

        $object = new User();
        $form = $this->factory->create(ProfileFormType::class, $object);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

}