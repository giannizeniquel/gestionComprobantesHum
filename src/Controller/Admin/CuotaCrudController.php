<?php

namespace App\Controller\Admin;

use App\Entity\Cuota;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class CuotaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cuota::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            NumberField::new('monto'),
            TextField::new('descripcion'),
            AssociationField::new('cursos', 'Cursos'),
        ];
    }
}
