<?php

namespace App\Controller\Admin;

use App\Entity\Pago;
use App\Form\PagoDetalleType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PagoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pago::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('observacion', 'Observaciones'),
            CollectionField::new('pagoDetalles', 'Detalle')
                ->allowDelete()
                ->setEntryIsComplex(true)
                ->setEntryType(PagoDetalleType::class)
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
        ];
    }
}
