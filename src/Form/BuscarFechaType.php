<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;



class BuscarFechaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dni', SearchType::class, array(
                'label' => 'DNI o Apellido',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px'
                ),
                'empty_data' => null,
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px'
                )
            ))
            ->add('startDate', DateType::class, array(
                'label' => 'Desde',
                'widget' => 'single_text',
                'html5' => true, // Cambia a true para utilizar la entrada de fecha HTML5
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px'
                ),
                'required' => false,
            ))
            ->add('endDate', DateType::class, array(
                'label' => 'Hasta',
                'widget' => 'single_text',
                'html5' => true, // Cambia a true para utilizar la entrada de fecha HTML5
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px'
                ),
                'required' => false,
            ))

            ->add('submit', SubmitType::class, [
                'label' => 'Buscar ', // Cambia el texto del botón si es necesario
                'attr' => ['class' => 'btn btn-light'], // Agrega clases CSS si es necesario
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'method' => 'GET',
            'action' => null,
        ));
    }
}
