<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\CallbackTransformer;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, ['required' => true, 'label' => 'Cédula de Identidad'])
            ->add('nombre', TextType::class, ['required' => true, 'label' => 'Nombre y Apellido'])
            ->add('email', TextType::class, ['required' => true, 'label' => 'email'])            
            ->add('telefono', TextType::class, ['required' => false, 'label' => 'Teléfono'])
            ->add('password', PasswordType::class, ['required' => true, 'label' => 'Contraseña'])
            ->add(
                'nivel',
                ChoiceType::class,
                [
                    'label' => 'Nivel',
                    'required' => true,
                    'multiple' => false,
                    'expanded' => false,
                    'choices' => [
                        'Administrador' => '99',
                        'Gestor de Discipulados' => '60',
                        'Discipulador' => '50',
                        'Discipulando' => '1',
                    ],
                ]

            )
            ->add(
                'estado',
                ChoiceType::class,
                [
                    'label' => 'Estado del Usuario',
                    'required' => true,
                    'multiple' => false,
                    'expanded' => false,
                    'choices' => [
                        'Activo' => '1',
                        'Inactivo' => '0',
                    ],
                ]
            )
            ->add('Roles', ChoiceType::class, [
                'label' => 'Rol del Usuario',
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    'Usuario' => 'ROLE_USER',
                    'Administrador' => 'ROLE_ADMIN',
                ],
            ])            
            ;

        // roles field data transformer
        $builder->get('Roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }

            ));             


        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
