<?php

namespace App\Controller\Admin;

use App\Entity\Curso;
use App\Entity\TipoCurso;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class TipoCursoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TipoCurso::class;
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
}
