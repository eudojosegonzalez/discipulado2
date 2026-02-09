<?php

namespace App\Form;

use App\Entity\Discipulo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class DiscipuloType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('cedula', TextType::class, ['required' => true, 'label' => 'Cédula de Identidad'])
        ->add('nombre', TextType::class, ['required' => true, 'label' => 'Nombre'])
        ->add('apellido', TextType::class, ['required' => true, 'label' => 'Apellido'])
        ->add('fecha_nac', DateType::class, ['required' => true, 'label' => 'Fecha de Nacimiento'])
        ->add('email', TextType::class, ['required' => true, 'label' => 'Email'])            
        ->add('sexo', 
            ChoiceType::class,
                [
                    'label' => 'Sexo',
                    'required' => true,
                    'multiple' => false,
                    'expanded' => false,
                    'choices' => [
                        'Hombre' => '1',
                        'Mujer' => '0',
                    ],
                ]        
        )                    
        ->add('telefono', TextType::class, ['required' => false, 'label' => 'Teléfono'])
        ->add(
            'estado',
            ChoiceType::class,
            [
                'label' => 'Estado del Discipulo',
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    'Activo' => '1',
                    'Inactivo' => '0',
                ],
            ]
        )
        ->add('direccion', TextareaType::class, ['required'=> false, 'label' => 'Dirección'])
        ->add('instruccion',TextType::class, ['required'=> false, 'label' => 'Instrucción'])
        ->add('tiempo_asistencia', TextType::class, ['required'=> false, 'label' => 'Tiempo de Asistencia  a la Iglesia (meses)'])
        ->add('fecha_registro', DateType::class, ['required'=> false, 'label' => 'Fecha de Registro'])
        ->add('observacion', TextareaType::class, ['required'=> false, 'label' => 'Observación'])
        
        ;          
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Discipulo::class,
        ]);
    }
}
