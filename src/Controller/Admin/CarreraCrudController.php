<?php

namespace App\Controller\Admin;

use App\Entity\Carrera;

use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;


class CarreraCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Carrera::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission('ROLE_ADMIN')
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
        ];
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
