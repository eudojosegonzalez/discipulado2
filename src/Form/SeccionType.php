<?php

namespace App\Form;

use App\Entity\Cohorte;
use App\Entity\Seccion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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

class SeccionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre',TextType::class, ['required' => true, 'label' => 'Nombre de la Sección'])
            ->add('fecha_creacion', DateType::class, ['required' => true, 'label' => 'Fecha de Creación'])
            ->add(
                'estado',
                ChoiceType::class,
                [
                    'label' => 'Estado de la Sección',
                    'required' => true,
                    'multiple' => false,
                    'expanded' => false,
                    'choices' => [
                        'Activo' => '1',
                        'Inactivo' => '0',
                    ],
                ])
            ->add('cohorte', EntityType::class, [
                'class' => Cohorte::class,
                'choice_label' => 'nombre',
                'required' => true,
                'placeholder' => 'Seleccione Cohorte',                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seccion::class,
        ]);
    }
}
