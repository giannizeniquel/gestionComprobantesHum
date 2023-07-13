<?php

namespace App\Controller\Admin;

use App\Entity\Curso;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

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

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
