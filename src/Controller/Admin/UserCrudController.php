<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Usuario')
            ->setEntityLabelInPlural('Usuarios')

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
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('apellido'),
            TextField::new('nombre'),
            TextField::new('dni'),
            TextField::new('email'),
            //TextField::new('password', 'Contraseña')->hideOnForm(),
            TextField::new('telefono','Teléfono'),
            TextField::new('domicilio'),
            ArrayField::new('roles'),
            AssociationField::new('cursos')
        ];
        
        // if (Crud::PAGE_INDEX === $pageName)
        // {
        //      yield IdField::new('id')->hideOnForm();
        //      yield TextField::new('nombre');
        //      yield TextField::new('apellido');
        // }
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
