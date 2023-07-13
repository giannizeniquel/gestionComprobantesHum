<?php

namespace App\Controller\Admin;

use App\Entity\Curso;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class CursoCrudController extends AbstractCrudController

{
    public static function getEntityFqcn(): string
    {
        return Curso::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Curso')
            ->setEntityLabelInPlural('Cursos')

            // in addition to a string, the argument of the singular and plural label methods
            // can be a closure that defines two nullable arguments: entityInstance (which will
            // be null in 'index' and 'new' pages) and the current page name
            // ->setEntityLabelInSingular(
            //     fn (?Curso $curso) => $curso ? $curso->toString() : 'Curso'
            // )
            // ->setEntityLabelInPlural(function (?Category $category, ?string $pageName) {
            //     return 'edit' === $pageName ? $category->getLabel() : 'Categories';
            // })

            // the Symfony Security permission needed to manage the entity
            // (none by default, so you can manage all instances of the entity)
            //->setEntityPermission('ROLE_EDITOR')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        if (Crud::PAGE_NEW === $pageName)
        {
            yield TextField::new('nombre');
            yield TextField::new('descripcion');
            yield TextField::new('observacion');
            yield BooleanField::new('activo');
            yield TextField::new('duracion');
            yield TextField::new('cuotas');
            yield NumberField::new('precio');
            yield IntegerField::new('capacidad');
            yield TextField::new('modalidad');
            yield AssociationField::new('tipo')->setFormTypeOptions(['by_reference' => false,])->autocomplete();
        }

        if (Crud::PAGE_EDIT === $pageName)
        {
            yield TextField::new('nombre');
            yield TextField::new('descripcion');
            yield TextField::new('observacion');
            yield BooleanField::new('activo');
            yield TextField::new('duracion');
            yield TextField::new('cuotas');
            yield NumberField::new('precio');
            yield IntegerField::new('capacidad');
            yield TextField::new('modalidad');
            yield AssociationField::new('tipo')->setFormTypeOptions(['by_reference' => true,])->autocomplete();
        }

        if (Crud::PAGE_INDEX === $pageName)
        {
            yield TextField::new('nombre');
            yield TextField::new('descripcion');
            yield TextField::new('observacion');
            yield BooleanField::new('activo');
            yield TextField::new('duracion');
            yield TextField::new('cuotas');
            yield NumberField::new('precio');
            yield IntegerField::new('capacidad');
            yield TextField::new('modalidad');
            yield AssociationField::new('tipo');
        }
    }

}
