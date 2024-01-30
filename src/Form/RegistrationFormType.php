<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2]),
                    new Assert\Callback([$this, 'validateCapitalLetter']),
                    new Assert\Regex([
                        'pattern' => '/\d/',
                        'match' => false,
                        'message' => 'Your first name cannot contain a number',
                    ]),
                ],
                'required'=>false,
            ])
            ->add('surname', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2]),
                    new Assert\Callback([$this, 'validateCapitalLetter']),
                    new Assert\Regex([
                        'pattern' => '/\d/',
                        'match' => false,
                        'message' => 'Your surname cannot contain a number',
                    ]),
                ],
                'required'=>false,
            ])
            ->add('postalCode', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^\d{4}$/',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/[a-zA-Z]/',
                        'match' => false,
                        'message' => 'Your postal code cannot contain a letter',
                    ]),
                ],
                'required' => false,
            ])
            ->add('birthdate', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'required'=>false,
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ],
                'required'=>false,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Password',
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['min' => 8]),
                        new Assert\Regex([
                            'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_]).{8,}$/',
                            'message' => 'Your password should be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.'
                        ]),
                    ],
                ],
                'required'=>false,
                'second_options' => [
                    'label' => 'Reconfirm Password',
                ],
                'invalid_message' => 'The password fields must match.',
            ]);
    }

    public function validateCapitalLetter($value, ExecutionContextInterface $context): void
    {
        if (!preg_match('/^[A-Z]/', $value)) {
            $context->buildViolation('The field should start with a capital letter')
                ->addViolation();
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
