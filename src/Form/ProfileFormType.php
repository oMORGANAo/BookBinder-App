<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProfileFormType extends AbstractType
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
            ->add('profilePicture', FileType::class, [
                'label' => 'Profile Picture',
                'mapped' => false,
                'required' => false,
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
