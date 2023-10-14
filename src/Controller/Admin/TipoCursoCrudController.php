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
            ->setEntityPermission('ROLE_ADMIN')
            ->setEntityLabelInSingular('Oferta')
            ->setEntityLabelInPlural('Ofertas');
    }

    public function configureFields(string $pageName): iterable
    {
        $user = $this->getUser();

        if($user){
            yield IdField::new('id')->hideOnForm();
            yield TextField::new('nombre');
            yield TextField::new('descripcion', 'Descripción');
            yield AssociationField::new('carrera','Propuesta');

            yield AssociationField::new('cursos');

            // para formularios embebidos
            //
            // CollectionField::new('cursos')
            //     ->allowDelete()
            //     ->setEntryIsComplex(true)

            //     ->setFormTypeOptions([
            //         'by_reference' => false,
            //     ])
        }

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
        $user = $this->getUser();

        if($user){
            return $actions
                ->add(Crud::PAGE_INDEX, Action::DETAIL)
                ->setPermission(Action::INDEX, 'ROLE_ADMIN')
                ->setPermission(Action::EDIT, 'ROLE_ADMIN')
                ->setPermission(Action::NEW, 'ROLE_ADMIN')
                ->setPermission(Action::DELETE, 'ROLE_ADMIN');
        }else{
            return $actions;
        }
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addHtmlContentToHead('<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>')
            ->addJsFile('/gestionComprobantesHum/public/front/js/base.js');
    }
}
