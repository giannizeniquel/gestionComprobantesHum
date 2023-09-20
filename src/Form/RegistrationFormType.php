<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ingrese nombre',
                    ]),
                ]
            ])
            ->add('apellido', TextType::class, [
                'label' => 'Apellido',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ingrese apellido',
                    ]),
                ]
            ])
            ->add('dni', TextType::class, [
                'label' => 'DNI',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ingrese DNI',
                    ]),
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ingrese un email',
                    ]),
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Contraseña',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'help' => 'Entre 6 y 8 caracteres',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ingrese una contraseña',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'La contraseña no cumple con el mínimo de {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 8,
                        'maxMessage' => 'La contraseña es demasiado larga, {{ limit }} caracteres como máximo',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
