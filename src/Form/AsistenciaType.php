<?php

namespace App\Form;

use App\Entity\Asistencia;
use App\Entity\Clases;
use App\Entity\Discipulo;
use App\Entity\Planificacion;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AsistenciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fecha_reg')
            ->add('planificacion', EntityType::class, [
                'class' => Planificacion::class,
                'choice_label' => 'id',
            ])
            ->add('discipulo', EntityType::class, [
                'class' => Discipulo::class,
                'choice_label' => 'id',
            ])
            ->add('clase', EntityType::class, [
                'class' => Clases::class,
                'choice_label' => 'id',
            ])
            ->add('usuario', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Asistencia::class,
        ]);
    }
}
