<?php

namespace App\Controller\Admin;

use App\Entity\Cuota;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
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

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addHtmlContentToHead('<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>')
            ->addJsFile('/gestionComprobantesHum/public/front/js/base.js');
    }
}
