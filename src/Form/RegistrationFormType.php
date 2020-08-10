<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'id' => 'email',
                    'class' => 'form-control',
                    'placeholder' => 'Email',
                    'autofocue' => true,
                ]
            ])
            ->add('username', TextType::class, [
                'label' => 'Username',
                'required' => true,
                'attr' => [
                    'id' => 'username',
                    'class' => 'form-control',
                    'placeholder' => 'Username',
                    'autofocue' => true,
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'Full Name',
                'required' => true,
                'attr' => [
                    'id' => 'fullname',
                    'class' => 'form-control',
                    'placeholder' => 'Full Name',
                    'autofocue' => true,
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'The password fields must match.',
                'required' => true,

                'first_options'  => [
                    'label' => 'Password',
                    'attr' => [
                        'placeholder' => 'Password',
                        'class' => 'password-field form-control',
                    ]
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                    'attr' => [
                        'placeholder' => 'Repeat Password',
                        'class' => 'password-field form-control',
                    ]
                ],

                'first_name'  => 'password',
                'second_name' => 'password_confirmation',
                
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
