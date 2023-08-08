<?php

namespace App\Controller\Admin;

use App\Entity\Pago;
use App\Form\PagoDetalleType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ComparisonFilter;

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
            AssociationField::new('user')->hideOnForm(),
            NumberField::new('monto'),
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

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
        ->add('user')
        ;
    }
}
