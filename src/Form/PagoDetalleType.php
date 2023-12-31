<?php

namespace App\Form;

use App\Entity\PagoDetalle;
use App\Entity\Cuota;
use App\Repository\CuotaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PagoDetalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $session = new Session;
        $idCurso = $session->get('idCurso');

        $builder
            ->add('cuotas', EntityType::class, [
                'class' => Cuota::class,
                'label' => 'Obligación/es',
                'multiple' => true,
                'query_builder' => function (CuotaRepository $cr) use ($idCurso) {
                    return $cr->findByCuotasDeCurso($idCurso);
                },
                'choice_label' => 'descripcion',
                'attr' => [
                    'class' => 'select2_cuotas',
                    'required' => true,
                    'onchange' => 'calcularMontoCuotas(this)'
                ]
            ])
            ->add('montoCuotas', NumberType::class, [
                'label' => 'Monto de Cuotas',
                'attr' => [
                    'required' => true,
                    'readonly' => true,
                ],
                'help' => 'Debe coincidir con el monto que figura en su comprobante',
            ])
            ->add('numeroTicket', null, [
                'label' => 'Numero de Transaccion',
            ])
            ->add('montoTicket')
            ->add('fechaTicket', DateType::class, [
                // renders it as a single text box
                'widget' => 'single_text',
            ])
            ->add('observacion')

            ->add('imageFile', VichFileType::class, [
                'label' => 'Subir Comprobante',
                'required' => true,
                'attr' => [
                    'required' => true,
                ]
            ])
            ->add('nombreArchivo', TextType::class, [
                'label' => 'Nombre Archivo',
                'required' => true,
                'attr' => [
                    'required' => true,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PagoDetalle::class,
        ]);
    }
}
