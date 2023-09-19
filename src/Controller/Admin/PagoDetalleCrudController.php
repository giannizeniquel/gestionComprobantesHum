<?php

namespace App\Controller\Admin;

use App\Entity\PagoDetalle;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class PagoDetalleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PagoDetalle::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [

        ];
    }
}
