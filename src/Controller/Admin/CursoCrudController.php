<?php

namespace App\Controller\Admin;

use App\Entity\Curso;
use App\Form\CuotaType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
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
            ->setEntityLabelInPlural('Cursos');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('tipo', 'Propuesta'),
            AssociationField::new('carrera', 'Oferta'),

            TextField::new('nombre'),
            TextField::new('corte', 'Cohorte'),
            TextField::new('descripcion', 'Descripción'),
            TextField::new('observacion', 'Observación'),
            BooleanField::new('activo'),
            IntegerField::new('cantidadCuotas'),
            //los users se van a asociar a los cursos a traves de la carga por lotes de users
            //dejamos activa la relacion en formularios desde user, para cubrir excepciones
            AssociationField::new('users', 'Alumnos inscriptos')->hideOnForm(),
            CollectionField::new('cuotas', 'Cuotas')
                ->allowDelete()
                ->setEntryIsComplex(true)
                ->setEntryType(CuotaType::class)
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
        ];
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
