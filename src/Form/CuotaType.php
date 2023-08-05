<?php

namespace App\Form;

use App\Entity\Cuota;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;

class CuotaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('monto')
            ->add('descripcion')
            
            ->add('fechaVencimiento', DateType::class, [
                // renders it as a single text box
                'widget' => 'single_text',
            ])
            ->add('numeroCuota')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cuota::class,
        ]);
    }
}
