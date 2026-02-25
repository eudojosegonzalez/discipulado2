<?php

namespace App\Form;

use App\Entity\Cohorte;
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

class CohorteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, ['required' => true, 'label' => 'Nombre de la Cohorte'])
            ->add('fecha_inicio', DateType::class, ['required' => true, 'label' => 'Fecha de Inicio'])
            ->add('fecha_fin', DateType::class, ['required' => true, 'label' => 'Fecha de Fin'])
            ->add('estado', ChoiceType::class, [
                'choices' => [
                    'Activo' => 1,
                    'Inactivo' => 0
                ],
                'required' => true,
                'label' => 'Estado de la Cohorte'
            ])            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cohorte::class,
        ]);
    }
}
