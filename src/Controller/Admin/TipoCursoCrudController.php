<?php

namespace App\Controller\Admin;

use App\Entity\TipoCurso;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class TipoCursoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TipoCurso::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Propuesta')
            ->setEntityLabelInPlural('Propuestas');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nombre'),
            TextField::new('descripcion', 'Descripción'),
            AssociationField::new('cursos')

            // para formularios embebidos
            //
            // CollectionField::new('cursos')
            //     ->allowDelete()
            //     ->setEntryIsComplex(true)

            //     ->setFormTypeOptions([
            //         'by_reference' => false,
            //     ])
        ];

        // if (Crud::PAGE_INDEX === $pageName)
        // {
        //     yield IdField::new('id');
        //     yield TextField::new('nombre');
        //     yield TextField::new('descripcion');
        //     yield AssociationField::new('cursos');
        // }
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addHtmlContentToHead('<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
                                    <script src="https://cdn.jsdelivr.net/jquery.queryloader2/3.2.2/jquery.queryloader2.min.js"></script>')
            ->addJsFile('/gestionComprobantesHum/public/front/js/base.js');
    }
}
