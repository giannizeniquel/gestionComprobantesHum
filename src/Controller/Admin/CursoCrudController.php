<?php

namespace App\Controller\Admin;

use App\Entity\Curso;
use App\Form\CuotaType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TipoCursoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


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
            ->setEntityPermission('ROLE_ADMIN')
            ->setEntityLabelInSingular('Curso')
            ->setEntityLabelInPlural('Cursos');
    }

    public function configureFields(string $pageName): iterable
    {
        $user = $this->getUser();

        if($user){
        
            yield IdField::new('id')->hideOnForm();
            yield TextField::new('nombre');
            yield AssociationField::new('carrera', 'Propuesta');
            yield AssociationField::new('tipo', 'Oferta')->renderAsNativeWidget();
        

            yield TextField::new('cohorte');
            yield TextField::new('descripcion', 'Descripción');
            yield TextField::new('observacion', 'Observación');
            yield BooleanField::new('activo');
            yield IntegerField::new('cantidadCuotas');
            //los users se van a asociar a los cursos a traves de la carga por lotes de users
            //dejamos activa la relacion en formularios desde user, para cubrir excepciones
            yield AssociationField::new('users', 'Alumnos inscriptos')->hideOnForm();
            yield CollectionField::new('cuotas', 'Cuotas')
                ->allowDelete()
                ->setEntryIsComplex(true)
                ->setEntryType(CuotaType::class)
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]);
        }
        
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
                //->setPermission('detail', 'ROLE_ADMIN');
        }else{
            return $actions;
        }
    }
    
    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addHtmlContentToHead('<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>')
            ->addJsFile('/gestionComprobantesHum/public/front/js/base.js')
            ->addJsFile('/gestionComprobantesHum/public/front/js/tipoCurso.js');

    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('id')
            ->add('tipo')
            ->add('carrera')
            ->add('nombre')
            ->add('cohorte')
        ;
    }

    /**
     * @Route("obtener_carrera", name="obtener_carrera",  methods={"POST"})
     */
    public function findTiposByCarreraAjax(Request $request, TipoCursoRepository $tipoCursoRepository): JsonResponse
    {
        //$carreraId='1';
        //GET
       // $carreraId = $request->query->get('carreraId');
        //POST
        $carreraId = $request->request->get('carreraId');
        $tipos = $tipoCursoRepository->findByTipoCursoAjax($carreraId);
       
        // Formatea los datos para la respuesta JSON
        $data = [];
        foreach ($tipos as $tipo) {
            $data[$tipo->getId()] = $tipo->getId();
        }

        return $this->json($data);
    }


}
