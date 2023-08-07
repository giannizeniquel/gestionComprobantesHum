<?php

namespace App\Controller\Admin;

use App\Entity\PagoDetalle;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class PagoDetalleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PagoDetalle::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            //AssociationField::new('pago')
            NumberField::new('monto'),
            TextField::new('numeroTicket'),
            NumberField::new('montoTicket'),
            //DateField::new('fechaTicket'),
            TextField::new('observacion'),
            AssociationField::new('archivo')
        ];
    }
}
