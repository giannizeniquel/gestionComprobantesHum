<?php

namespace App\Controller\Admin;

use App\Entity\TipoCurso;
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

    
    public function configureFields(string $pageName): iterable
    {
        if (Crud::PAGE_NEW === $pageName)
        {
            yield TextField::new('nombre');
            yield TextField::new('descripcion');
            yield AssociationField::new('cursos')->setFormTypeOptions(['by_reference' => false,])->autocomplete();
        }

        if (Crud::PAGE_EDIT === $pageName)
        {
            yield TextField::new('nombre');
            yield TextField::new('descripcion');
            yield AssociationField::new('cursos')->autocomplete();
        }

        if (Crud::PAGE_INDEX === $pageName)
        {
            yield IdField::new('id');
            yield TextField::new('nombre');
            yield TextField::new('descripcion');
            yield AssociationField::new('cursos');
        }

        if (Crud::PAGE_DETAIL === $pageName)
        {
            yield IdField::new('id');
            yield TextField::new('nombre');
            yield TextField::new('descripcion');
            yield AssociationField::new('cursos');
        }
    }
}
