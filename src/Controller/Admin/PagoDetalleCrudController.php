<?php

namespace App\Controller\Admin;

use App\Entity\PagoDetalle;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class PagoDetalleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PagoDetalle::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [];
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addHtmlContentToHead('<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>')
            ->addJsFile('/gestionComprobantesHum/public/front/js/base.js');
    }
}
