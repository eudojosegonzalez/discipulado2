<?php

namespace App\Form;

use App\Entity\Clases;
use App\Entity\Modulo;
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
use Symfony\Component\Form\CallbackTransformer;

class ClasesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo', TextType::class, ['required' => true, 'label' => 'Título de la Lección'])
            ->add('sinopsis', 
                TextareaType::class,
        [
                'required' => false, 
                 'label' => 'Sinopsis', 
                 'attr' => ['data-controller' => 'editor',
                 'rows' => 15]
                 ])
            ->add('orden', NumberType::class, ['required' => false, 'label' => 'Orden'])
            ->add('modulo', EntityType::class, [
                'class' => Modulo::class,
                'choice_label' => 'nombre',
                'label' => 'Módulo',
                'required' => true,
                'placeholder' => 'Seleccione un módulo',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clases::class,
        ]);
    }
}
