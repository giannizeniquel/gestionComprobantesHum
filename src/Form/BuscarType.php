<?php

namespace App\Form;

use App\Entity\Pago;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class BuscarType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dni')
            ->add('submit', SubmitType::class, [
                'label' => 'Buscar dni o apellido del estudiante', // Cambia el texto del botón si es necesario
                'attr' => ['class' => 'btn btn-light'], // Agrega clases CSS si es necesario
            ]);
    }

    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'method' => 'GET',
            'action' => null,
        ));
    }
}