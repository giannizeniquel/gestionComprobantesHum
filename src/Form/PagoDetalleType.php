<?php

namespace App\Form;

use App\Entity\PagoDetalle;
use App\Entity\Archivo;
use App\Form\ArchivoType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PagoDetalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('monto')
            ->add('cuotas')
            ->add('numeroTicket', null, [
                'label' => 'Numero de Transaccion',
            ])
            ->add('montoTicket')
            ->add('fechaTicket', DateType::class, [
                // renders it as a single text box
                'widget' => 'single_text',
            ])
            ->add('observacion')
            
            ->add('comprobantes', CollectionType::class, [
                'label' => 'Comprobante',
                'entry_type' => ArchivoType::class,
                'entry_options' => [
                    'label' => false
                ],
                'allow_add' => true,
                'allow_delete' => true,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PagoDetalle::class,
        ]);
    }
}
