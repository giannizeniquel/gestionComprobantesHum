<?php

namespace App\Controller\Admin;

use App\Entity\Curso;
use App\Entity\Cuota;
use App\Entity\Carrera;
use App\Form\CuotaType;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

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
        return [
            IdField::new('id'),
            AssociationField::new('tipo', 'Tipo Curso'),
            AssociationField::new('carrera', 'Tipo Carrera'),

            TextField::new('nombre'),
            TextField::new('corte'),
            TextField::new('descripcion', 'Descripción'),
            TextField::new('observacion', 'Observación'),
            BooleanField::new('activo'),
            IntegerField::new('cantidadCuotas'),
            AssociationField::new('users', 'Alumnos inscriptos'),
            CollectionField::new('cuotas', 'Cuotas')
                ->allowDelete()
                ->setEntryIsComplex(true)
                ->setEntryType(CuotaType::class)
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),

            // TextField::new('duracion', 'Duración'),
            // NumberField::new('precio'),
            // IntegerField::new('capacidad'),
            // TextField::new('modalidad'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
    
}
