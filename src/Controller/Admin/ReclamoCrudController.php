<?php

namespace App\Controller\Admin;

use App\Entity\Reclamo;
use App\Form\MensajeType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class ReclamoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reclamo::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield AssociationField::new('user')->hideOnForm();
        yield AssociationField::new('pago');
        yield BooleanField::new('estado')->setPermission('ROLE_ADMIN');
        yield CollectionField::new('mensajes', 'Mensajes')
        ->hideOnDetail()
        ->setEntryIsComplex(true)
        ->setEntryType(MensajeType::class)
        ->setFormTypeOptions([
            'by_reference' => false,
        ]);
        //yield DateTimeField::new('created_at');

    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addHtmlContentToHead('<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>')
            ->addJsFile('/gestionComprobantesHum/public/front/js/base.js');
    }
    
}
