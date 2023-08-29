<?php

namespace App\Form;

use App\Entity\Archivo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CargaExcelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('file', FileType::class, [
            'label' => 'Selecciona un archivo',
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Enviar Archivo',
        ]);
    }

  
}