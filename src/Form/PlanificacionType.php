<?php

namespace App\Form;

use App\Entity\Aula;
use App\Entity\Clases;
use App\Entity\Planificacion;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanificacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fecha', DateType::class, ['required' => true, 'label' => 'Fecha de la Planificación'])
            ->add('observacion', TextareaType::class, ['required' => false, 'label' => 'Observaciones'])
            ->add('leccion', EntityType::class, [
                'class' => Clases::class,
                'choice_label' => 'Titulo',
                'label' => 'Lección',
            ])
            ->add('aula', EntityType::class, [
                'class' => Aula::class,
                'choice_label' => 'Nombre',
            ])
            ->add('usuario', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'Nombre',
                'label' => 'Discipulador',
            ])
            ->add(
                'estado',
                ChoiceType::class,
                [
                    'label' => 'Estado de la Planificación',
                    'required' => true,
                    'multiple' => false,
                    'expanded' => false,
                    'choices' => [
                        'Activo' => '1',
                        'Inactivo' => '0',
                    ],
                ]
        )            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Planificacion::class,
        ]);
    }
}
