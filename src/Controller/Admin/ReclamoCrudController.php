<?php

namespace App\Controller\Admin;

use App\Entity\Reclamo;
use App\Form\MensajeType;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Request;

class ReclamoCrudController extends AbstractCrudController
{

    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Reclamo::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $user = $this->getUser();
        if($user){
            yield IdField::new('id')->hideOnForm();
            yield AssociationField::new('user')->hideOnForm();
            if ((Crud::PAGE_NEW === $pageName || Crud::PAGE_EDIT === $pageName) && (!in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
                yield AssociationField::new('pago')
                    ->setFormTypeOptions([
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('pago')
                                ->join('pago.user', 'u')
                                ->where('u.id = :userId')
                                ->andWhere('pago.id = :idPago')
                                ->setParameter('userId', $this->getUser()->getId())
                                ->setParameter('idPago', $this->get('session')->get('idPago'));
                        },
                        'by_reference' => true,
                    ])
                    ->renderAsNativeWidget();
            } else {
                yield AssociationField::new('pago')->renderAsNativeWidget();
            }
    
            if ((Crud::PAGE_DETAIL === $pageName)){
                yield BooleanField::new('estado');
            }else{
                yield BooleanField::new('estado')->setPermission('ROLE_ADMIN');
            }
            
            if(Crud::PAGE_EDIT === $pageName){
                //si es super_admin dejamos eliminar mensajes
                if (in_array('ROLE_SUPER_ADMIN', $user->getRoles())){
                    yield CollectionField::new('mensajes', 'Mensajes')
                        ->setEntryIsComplex(true)
                        ->setEntryType(MensajeType::class)
                        ->renderExpanded()
                        ->setFormTypeOptions([
                            'by_reference' => false,
                        ]);
                }else{
                    yield CollectionField::new('mensajes', 'Mensajes')
                        ->allowDelete(false)
                        ->setEntryIsComplex(true)
                        ->setEntryType(MensajeType::class)
                        ->renderExpanded()
                        ->setFormTypeOptions([
                            'by_reference' => false,
                        ]);
                }
                
            }else{
                yield CollectionField::new('mensajes', 'Mensajes')
                    ->setEntryIsComplex(true)
                    ->setEntryType(MensajeType::class)
                    ->renderExpanded()
                    ->setFormTypeOptions([
                        'by_reference' => false,
                    ]);
            }
        }
        

    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addHtmlContentToHead('<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
                                    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                                    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>')
            ->addJsFile('/gestionComprobantesHum/public/front/js/base.js')
            ->addJsFile('/gestionComprobantesHum/public/front/js/reclamo.js');
    }

    public function configureActions(Actions $actions): Actions
    {
        $user = $this->getUser();

        if($user){
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                return $actions
                    ->add(Crud::PAGE_INDEX, Action::DETAIL)
                    ->setPermission(Action::INDEX, 'ROLE_ADMIN')
                    //->setPermission(Action::EDIT, 'ROLE_SUPER_ADMIN')
                    ->setPermission(Action::NEW, 'ROLE_SUPER_ADMIN')
                    ->setPermission(Action::DELETE, 'ROLE_SUPER_ADMIN');
            } else {
                return $actions
                    ->add(Crud::PAGE_INDEX, Action::DETAIL)
                    ->setPermission(Action::INDEX, 'ROLE_ADMIN');
            }
        }else{
            return $actions;
        }
    }

    
    public function obtenerIdPago(AdminContext $context, Request $request)
    {
        $idPago = $context->getRequest()->get('idPago');
        $idReclamo = $context->getRequest()->get('idReclamo');
        $accion = $context->getRequest()->get('accionEditar');
        $session = $request->getSession();
        $session->set('idPago', $idPago);

        if ($accion == 'editar') {
            $url = $this->adminUrlGenerator
                ->setController(ReclamoCrudController::class)
                ->setAction('edit')
                ->setEntityId($idReclamo)
                ->generateUrl();
        } else {
            $url = $this->adminUrlGenerator
                ->setController(ReclamoCrudController::class)
                ->setAction('new')
                ->generateUrl();
        }

        return $this->redirect($url);
    }
    
}
