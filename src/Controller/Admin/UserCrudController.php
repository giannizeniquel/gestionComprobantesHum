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
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use Symfony\Component\Console\Helper\Helper;

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
        $user = $this->getUser();
        if($user){
            if (!in_array('ROLE_ADMIN', $user->getRoles())) {
                yield FormField::addPanel('Datos Personales')
                    ->setHelp('Si necesita modificar estos datos contactarse con un administrador.');
            }else {
                yield FormField::addPanel('Datos Personales');
            }
            yield IdField::new('id', 'ID Usuario')->hideOnForm();
            yield TextField::new('apellido');
            yield TextField::new('nombre');
            yield TextField::new('dni');
            yield FormField::addPanel('Contacto');
            yield TextField::new('email');
            //yield TextField::new('password', 'Contraseña')->hideOnForm();
            yield TextField::new('telefono', 'Teléfono');
            yield TextField::new('domicilio');
            yield FormField::addPanel('Configuraciones de Administrador')->setPermission('ROLE_ADMIN');
            yield ArrayField::new('roles')->setPermission('ROLE_ADMIN'); //TODO: probar con CollectionField y asocia un type con opciones predefinidas
            yield AssociationField::new('cursos', 'Cursos inscriptos')->setPermission('ROLE_ADMIN');
        }else{
            return $this->redirectToRoute('app_login');
        }
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::INDEX, 'ROLE_ADMIN');
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addHtmlContentToHead('<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
                                    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                                    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                                    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css"/>
                                    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>')
            ->addJsFile('/gestionComprobantesHum/public/front/js/user.js')
            ->addJsFile('/gestionComprobantesHum/public/front/js/base.js');
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('id')
            ->add('apellido')
            ->add('dni')
            ->add('email')   
        ;
    }

    /**
     * @Route("/admin/misCursos", name="misCursos")
     */ public function obtenerCursosUsuario(UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $userId = $this->getUser()->getId();
        $cursos = $userRepository
            ->findByMisCursos($userId);

        return $this->render('user/userCursos.html.twig', [
            'cursos' => $cursos,
            'userId' => $userId
        ]);
    }

    /**
     * @Route("/admin/misPagos", name="misPagos")
     */
    public function obtenerPagosUsuario(UserRepository $userRepository): Response
    {
        $userId = $this->getUser()->getId();
        $pagos = $userRepository
            ->findByMisPagos($userId);

        return $this->render('user/userPagos.html.twig', ['pagos' => $pagos]);
    }
}
