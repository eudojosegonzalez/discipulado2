<?php

namespace App\Form;

use App\Entity\Discipulo;
use App\Entity\Seccion;
use App\Entity\SeccionAlumno;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeccionAlumnoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fecha_creacion')
            ->add('estado')
            ->add('seccion', EntityType::class, [
                'class' => Seccion::class,
                'choice_label' => 'id',
            ])
            ->add('discipulo', EntityType::class, [
                'class' => Discipulo::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SeccionAlumno::class,
        ]);
    }
}
